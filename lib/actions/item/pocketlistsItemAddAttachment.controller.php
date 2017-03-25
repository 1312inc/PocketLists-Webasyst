<?php

class pocketlistsItemAddAttachmentController extends waJsonController
{
    protected $errors = array();

    public function execute()
    {
        $this->response['files'] = array();

        $this->getStorage()->close();
        if (waRequest::server('HTTP_X_FILE_NAME')) {
            $name = waRequest::server('HTTP_X_FILE_NAME');
            $size = waRequest::server('HTTP_X_FILE_SIZE');
            $file_path = wa()->getTempPath('shop/upload/') . $name;
            $append_file = is_file($file_path) && $size > filesize($file_path);
            clearstatcache();
            file_put_contents($file_path, fopen('php://input', 'rb'), $append_file ? FILE_APPEND : 0);
            $file = new waRequestFile(array(
                'name'     => $name,
                'type'     => waRequest::server('HTTP_X_FILE_TYPE'),
                'size'     => $size,
                'tmp_name' => $file_path,
                'error'    => 0,
            ));

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

    private function validFile(waRequestFile $file)
    {
        $name_ext = $file->extension;
        if (strpos(strtolower($name_ext), 'php') !== false) {
            $name_ext = 'php';
        }
        if (in_array($name_ext, array('php', 'phtml'))) {
            return sprintf(_w("Files with extension .%s are not allowed to security considerations."),
                $name_ext);
        }
        return true;
    }

    public function save(waRequestFile $file)
    {
        $im = new pocketlistsItemModel();
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $filevalid = $this->validFile($file);
        if ($filevalid === true) {
            $item = $im->getById($item_id);
            $path_public = wa()->getDataPath('attachments/' . $item['id'] . '/', true);
            if ($item && is_writable($path_public)) {
                $name = $file->name;

                if ($file->uploaded()) {
                    if (!preg_match('//u', $name)) {
                        $tmp_name = @iconv('windows-1251', 'utf-8//ignore', $name);
                        if ($tmp_name) {
                            $name = $tmp_name;
                        }
                    }
                    if (file_exists($path_public . DIRECTORY_SEPARATOR . $name)) {
                        $i = strrpos($name, '.');
                        $ext = substr($name, $i + 1);
                        $name = substr($name, 0, $i);
                        $i = 1;
                        while (file_exists($path_public . DIRECTORY_SEPARATOR . $name . '-' . $i . '.' . $ext)) {
                            $i++;
                        }
                        $name = $name . '-' . $i . '.' . $ext;
                    }
                    $type = null;
                    if (exif_imagetype($file->tmp_name)) {
                        $type = 'image';
                    }
                    if ($file->moveTo($path_public, $name)) {
                        $pa = new pocketlistsAttachmentModel();
                        $attachment_id = $pa->insert(array(
                            'item_id'  => $item['id'],
                            'filename' => $name,
                            'filetype' => $type,
                        ));

                        $this->errors = [];
                        return array(
                            'path' => wa()->getDataUrl('attachments/' . $item['id'] . '/', true),
                            'name' => $file->name,
                            'type' => $file->type,
                            'size' => $file->size,
                            'id'   => $attachment_id,
                        );
                    } else {
                        $this->errors[] = sprintf(_w('Failed to upload file %s.'), $file->name);
                    }
                } else {
                    $this->errors[] = sprintf(_w('Failed to upload file %s.'), $file->name) . ' (' . $file->error . ')';
                }
            } else {
                $this->errors[] = _w('No item with such ID');
            }
        } else {
            $this->errors[] = $filevalid;
        }
        return false;
    }
}
