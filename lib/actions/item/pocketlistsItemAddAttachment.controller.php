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
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $filevalid = $this->validFile($file);
        if ($filevalid === true) {
            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $item */
            $item = $itemFactory->findById($item_id);
            if (!$item) {
                return false;
            }

            $path_public = wa()->getDataPath('attachments/'.$item->getId().'/', true, pocketlistsHelper::APP_ID);
            if ($item && is_writable($path_public)) {
                $name = $file->name;

                if ($file->uploaded()) {
                    if (!preg_match('//u', $name)) {
                        $tmp_name = @iconv('windows-1251', 'utf-8//ignore', $name);
                        if ($tmp_name) {
                            $name = $tmp_name;
                        }
                    }

                    if (file_exists($path_public.DIRECTORY_SEPARATOR.$name)) {
                        $i = strrpos($name, '.');
                        $ext = substr($name, $i + 1);
                        $name = substr($name, 0, $i);
                        $i = 1;
                        while (file_exists($path_public.DIRECTORY_SEPARATOR.$name.'-'.$i.'.'.$ext)) {
                            $i++;
                        }

                        $name = $name.'-'.$i.'.'.$ext;
                    }

                    $type = null;
                    if (exif_imagetype($file->tmp_name)) {
                        $type = 'image';
                    }

                    if ($file->moveTo($path_public, $name)) {
                        /** @var pocketlistsFactory $attachmentFactory */
                        $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
                        /** @var pocketlistsAttachment $attachment */
                        $attachment = $attachmentFactory->createNew();
                        $attachment
                            ->setFilename($name)
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

                        return [
                            'path' => wa()->getDataUrl(
                                'attachments/'.$item->getId().'/',
                                true,
                                pocketlistsHelper::APP_ID
                            ),
                            'name' => $file->name,
                            'type' => $file->type,
                            'size' => $file->size,
                            'id'   => $attachment->getId(),
                        ];
                    }

                    $this->errors[] = sprintf(_w('Failed to upload file %s.'), $file->name);
                } else {
                    $this->errors[] = sprintf(_w('Failed to upload file %s.'), $file->name).' ('.$file->error.')';
                }
            } else {
                $this->errors[] = _w('No item with such ID');
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
