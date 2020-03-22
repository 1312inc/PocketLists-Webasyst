<?php

/**
 * Class pocketlistsItemAddAttachmentController
 */
class pocketlistsItemAddAttachmentController extends pocketlistsJsonController
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @throws waException
     */
    public function execute()
    {
        $this->response['files'] = [];

        $this->getStorage()->close();
        if (waRequest::server('HTTP_X_FILE_NAME')) {
            $name = waRequest::server('HTTP_X_FILE_NAME');
            $size = waRequest::server('HTTP_X_FILE_SIZE');
            $file_path = wa()->getTempPath('shop/upload/').$name;
            $append_file = is_file($file_path) && $size > filesize($file_path);
            clearstatcache();
            file_put_contents($file_path, fopen('php://input', 'rb'), $append_file ? FILE_APPEND : 0);
            $file = new waRequestFile(
                [
                    'name' => $name,
                    'type' => waRequest::server('HTTP_X_FILE_TYPE'),
                    'size' => $size,
                    'tmp_name' => $file_path,
                    'error' => 0,
                ]
            );

            try {
                $f = $this->save($file);
                if ($f) {
                    $this->response['files'][] = $f;
                }
            } catch (Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        } else {
            $files = waRequest::file('files');
            foreach ($files as $file) {
                if ($file->error_code != UPLOAD_ERR_OK) {
                    $this->errors = $file->error;
                } else {
                    try {
                        $f = $this->save($file);
                        if ($f) {
                            $this->response['files'][] = $f;
                        }
                    } catch (Exception $e) {
                        $this->errors = $e->getMessage();
                    }
                }
            }
        }
    }

    /**
     * @param waRequestFile $file
     *
     * @return array|bool
     * @throws waException
     */
    public function save(waRequestFile $file)
    {
        $uploadedFile = pocketlistsUploadedFileVO::createFromWaRequestFile($file);

        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $filevalid = $this->validFile($uploadedFile->getFile());
        if ($filevalid === true) {
            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $item */
            $item = $itemFactory->findById($item_id);
            if ($item instanceof pocketlistsItem) {
                $uploadedFile->setItemId($item->getId());
            }

            if (is_writable($uploadedFile->getPath())) {
                if ($uploadedFile->getFile()->uploaded()) {
                    $name = $uploadedFile->getName();
                    if (!preg_match('//u', $name)) {
                        $tmp_name = @iconv('windows-1251', 'utf-8//ignore', $name);
                        if ($tmp_name) {
                            $name = $tmp_name;
                        }
                    }

                    if (file_exists($uploadedFile->getFullPath())) {
                        $i = strrpos($name, '.');
                        $ext = substr($name, $i + 1);
                        $name = substr($name, 0, $i);
                        $i = 1;
                        while (file_exists(sprintf('%s%s-%s.%s', $uploadedFile->getPath(), $name, $i, $ext))) {
                            $i++;
                        }

                        $uploadedFile->setName(sprintf('%s-%s.%s', $name, $i, $ext));
                    }

                    $type = null;
                    if (exif_imagetype($uploadedFile->getFile()->tmp_name)) {
                        $type = 'image';
                    }

                    if ($uploadedFile->getFile()->moveTo($uploadedFile->getPath(), $uploadedFile->getName())) {
                        $return = [];
                        if (!$uploadedFile->isTemp()) {
                            /** @var pocketlistsFactory $attachmentFactory */
                            $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
                            /** @var pocketlistsAttachment $attachment */
                            $attachment = $attachmentFactory->createNew();
                            $attachment
                                ->setFilename($uploadedFile->getName())
                                ->setFiletype($type)
                                ->setItemId($item->getId());

                            $attachmentFactory->insert($attachment);

                            $this->logService->add(
                                $this->logService->getFactory()->createNewAttachmentLog(
                                    (new pocketlistsLogContext())
                                        ->setItem($item)
                                        ->setAttachment($attachment)
                                )
                            );

                            $this->errors = [];

                            $return = ['id' => $attachment->getId()];
                        }

                        return array_merge(
                            [
                                'url' => $uploadedFile->getUrl(),
                                'name' => $uploadedFile->getName(),
                                'type' => $uploadedFile->getType(),
                                'size' => $uploadedFile->getSize(),
                                'id' => -1,
                            ],
                            $return
                        );
                    }

                    $this->errors[] = sprintf(_w('Failed to upload file %s.'), $file->name);
                } else {
                    $this->errors[] = sprintf(_w('Failed to upload file %s.'), $file->name).' ('.$file->error.')';
                }
            } else {
                $this->errors[] = sprintf_wp('Path %s is not writable', $uploadedFile->getPath());
            }
        } else {
            $this->errors[] = $filevalid;
        }

        return false;
    }

    /**
     * @param waRequestFile $file
     *
     * @return bool|string
     */
    private function validFile(waRequestFile $file)
    {
        $name_ext = $file->extension;

        if (stripos($name_ext, 'php') !== false) {
            $name_ext = 'php';
        }

        if (in_array($name_ext, ['php', 'phtml', 'htaccess'])) {
            return sprintf(
                _w('Files with extension .%s are not allowed to security considerations.'),
                $name_ext
            );
        }

        return true;
    }
}
