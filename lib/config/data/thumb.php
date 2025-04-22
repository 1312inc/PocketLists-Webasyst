<?php

function image_not_found()
{
    header('Location: /wa-apps/pocketlists/img/image-not-found.png');
    exit;
}

function getThumbUrl($file_in, $file_out, $size)
{
    $result = '';
    if (!file_exists($file_in) || !is_int($size) || $size < 1) {
        return $result;
    }
    try {
        $temp = wa()->getTempPath().uniqid().$file_out;
        waFiles::copy($file_in, $temp);

        $img = waImage::factory($temp);
        if (method_exists($img, 'fixImageOrientation')) {
            $img->fixImageOrientation();
        }

        $aspect_ratio = floatval($img->height / $img->width);
        if ($aspect_ratio > 1) {
            $img->resize(intval($size / $aspect_ratio), $size);
        } else {
            $img->resize($size, intval($size * $aspect_ratio));
        }
        if ($img->save($temp)) {
            $result = (waFiles::move($temp, $file_out) ? $file_out : '');
        }
    } catch (Exception $ex) {
        waFiles::delete($file_out,true);
        if (class_exists('waLog')) {
            waLog::log($ex->getMessage(), 'wa-apps/pocketlists/thumb.log');
        }
    }

    return $result;
}

$path = realpath(dirname(__FILE__).'/../../../../../');
$config_path = $path.'/wa-config/SystemConfig.class.php';
if (!file_exists($config_path)) {
    image_not_found();
}
require_once($config_path);
$config = new SystemConfig();
waSystem::getInstance(null, $config);

wa('pocketlists')->getStorage()->close();
$request_file = wa()->getConfig()->getRequestUrl(true, true);
$public_path = $path.'/wa-data/public/pocketlists/attachments';
$protected_path = $path.'/wa-data/protected/pocketlists/attachments';

$request_parts = explode('/', $request_file);
if (count($request_parts) !== 2 || !is_numeric($request_parts[0])) {
    image_not_found();
}
$item_id = (int) array_shift($request_parts);
$file_orig_name = array_shift($request_parts);

$file_parts = explode('.', $file_orig_name);
if (count($file_parts) < 3) {
    image_not_found();
}

$file_ext = array_pop($file_parts);
$preview_size = (int) array_pop($file_parts);
$file_name = implode('.', $file_parts);
if (
    !in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])
    || $preview_size !== pocketlistsAttachment::PREVIEW_SIZE
) {
    image_not_found();
}

$file_in = "$protected_path/$item_id/$file_name.$file_ext";
$file_out = "$public_path/$item_id/$file_orig_name";
if (file_exists($file_in)) {
    // ищем оригинал в защищенной директории
    if ($preview_url = getThumbUrl($file_in, $file_out, $preview_size)) {
        waFiles::readFile($preview_url);
        exit();
    }
}

$file_in = "$public_path/$item_id/$file_name.$file_ext";
if (file_exists($file_in)) {
    // ищем оригинал в публичной директории
    if ($preview_url = getThumbUrl($file_in, $file_out, $preview_size)) {
        waFiles::readFile($preview_url);
        exit();
    }
}

image_not_found();
