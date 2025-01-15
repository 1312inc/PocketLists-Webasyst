<?php

class pocketlistsFileDownloadController extends waController
{
    public function execute()
    {
        $id = (int) $this->getRequest()->param('id', 0, waRequest::TYPE_INT);
        $thumb_px = waRequest::get('thumb', 0, waRequest::TYPE_INT);

        if ($id < 1) {
            throw new pocketlistsNotFoundException();
        }

        $file = pl2()->getModel(pocketlistsAttachment::class)->getById($id);
        if (!$file) {
            throw new pocketlistsNotFoundException();
        }

        wa()->getResponse()->addHeader('Cache-Control', 'private, no-transform');

        $file_ext = mb_strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
        if (!empty($thumb_px) && in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if ($thumb_px < 10) {
                throw new pocketlistsAssertException(sprintf('Variable %s should be greater then %s', 'thumb', 10), 400);
            }
            $thumb_path = $this->getThumb($file, $thumb_px);
            if (!empty($thumb_path)) {
                $this->redirect($thumb_path);
            }
        }

        if ($file['storage'] === 'public') {
            $path_public = wa()->getDataUrl('attachments/%s/%s', true, pocketlistsHelper::APP_ID, true);
            $this->redirect(sprintf($path_public, $file['item_id'], $file['filename']));
        }

        $path_private = wa()->getDataPath('attachments/%s/%s', false, pocketlistsHelper::APP_ID);
        waFiles::readFile(sprintf($path_private, $file['item_id'], $file['filename']));
    }

    protected function getThumb($file, $size)
    {
        $path = "attachments/${file['item_id']}/";
        $path_public = wa()->getDataPath($path, true, pocketlistsHelper::APP_ID);
        $thumb_name = pathinfo($file['filename'], PATHINFO_FILENAME).".$size.".pathinfo($file['filename'], PATHINFO_EXTENSION);
        $thumb_path = "$path_public$thumb_name";

        if (file_exists($thumb_path)) {
            return wa()->getDataUrl($path.$thumb_name, true, pocketlistsHelper::APP_ID);
        }
        $path_private = wa()->getDataPath($path, false, pocketlistsHelper::APP_ID);
        if (file_exists($path_private.$file['filename'])) {
            waFiles::copy($path_private.$file['filename'], $thumb_path);
        } elseif (file_exists($path_public.$file['filename'])) {
            waFiles::copy($path_public.$file['filename'], $thumb_path);
        } else {
            return '';
        }

        $img = waImage::factory($thumb_path);
        if (method_exists($img, 'fixImageOrientation')) {
            $img->fixImageOrientation();
        }

        $img = $this->scale($img, $size);

        return $img->save($thumb_path) ? wa()->getDataUrl($path.$thumb_name, true, pocketlistsHelper::APP_ID) : '';
    }

    protected function scale(waImage $img, $size)
    {
        $aspect_ratio = floatval($img->height / $img->width);
        if ($aspect_ratio > 1) {
            $img->resize(intval($size / $aspect_ratio), $size);
        } else {
            $img->resize($size, intval($size * $aspect_ratio));
        }

        return $img;
    }
}
