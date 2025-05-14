<?php

/**
 * Class pocketlistsAttachment
 */
class pocketlistsAttachment extends pocketlistsEntity
{
    const PREVIEW_SIZE = 600;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $item_id;

    /**
     * @var string
     */
    private $filename = '';

    /**
     * @var string
     */
    private $ext = '';

    /**
     * @var int|null
     */
    private $size = null;

    /**
     * @var string
     */
    private $filetype = null;

    /**
     * @var string
     */
    private $storage = 'protected';

    /**
     * @var string
     */
    private $upload_datetime;

    /**
     * @var string|null
     */
    private $uuid;

    /**
     * @var pocketlistsItem
     */
    private $item;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return pocketlistsAttachment
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return pocketlistsAttachment
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return pocketlistsAttachment
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        $this->ext = self::getExtension($filename);

        return $this;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param $ext
     * @return pocketlistsAttachment
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $size
     * @return pocketlistsAttachment
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return string
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param $storage
     *
     * @return pocketlistsAttachment
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDatetime()
    {
        return $this->upload_datetime;
    }

    /**
     * @param $upload_datetime
     *
     * @return pocketlistsAttachment
     */
    public function setUploadDatetime($upload_datetime)
    {
        $this->upload_datetime = $upload_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param $uuid
     * @return pocketlistsAttachment
     */
    public function setUuid($uuid)
    {
        $this->uuid = (empty($uuid) ? null : trim($uuid));

        return $this;
    }

    /**
     * @return pocketlistsItem
     * @throws waException
     */
    public function getItem()
    {
        if ($this->item === null && $this->item_id) {
            $this->item = pl2()->getEntityFactory(pocketlistsItem::class)->findById($this->item_id);
        }

        return $this->item;
    }

    /**
     * @param $attachment_data
     * @return array
     * @throws waException
     */
    public static function setUrl($attachment_data = [])
    {
        static $url_pub;
        static $url_pri;

        $attachment_data['download_url'] = '';
        $attachment_data['preview_url'] = '';
        $available_extension = ['jpg', 'jpeg', 'png', 'gif'];
        if (empty($attachment_data['item_id']) || empty($attachment_data['filename'])) {
            return $attachment_data;
        }
        if (!isset($url_pub)) {
            $url_pub = wa()->getDataUrl('attachments/%s/%s', true, pocketlistsHelper::APP_ID, true);
        }
        if (!isset($url_pri)) {
            $url_pri = wa()->getUrl(true).wa()->getConfig()->getBackendUrl().'/'.pocketlistsHelper::APP_ID.'/download/%s';
        }

        if (ifset($attachment_data, 'storage', 'protected') === 'protected') {
            $attachment_data['download_url'] = sprintf($url_pri, $attachment_data['id']);
        } else {
            $attachment_data['download_url'] = sprintf($url_pub, $attachment_data['item_id'], $attachment_data['filename']);
        }
        $attach_ext = self::getExtension($attachment_data['filename']);
        if (waImage::isWebpSupported()) {
            $available_extension[] = 'webp';
        }
        if (in_array($attach_ext, $available_extension)) {
            if ($attachment_data['storage'] === 'public') {
                $attachment_data['preview_url'] = $attachment_data['download_url'];
            } else {
                $attach_name = pathinfo($attachment_data['filename'], PATHINFO_FILENAME).'.'.pocketlistsAttachment::PREVIEW_SIZE.'.'.$attach_ext;
                $attachment_data['preview_url'] = sprintf($url_pub, $attachment_data['item_id'], $attach_name);
            }
        }

        return $attachment_data;
    }

    /**
     * @param $file_name
     * @return string
     */
    public static function getExtension($file_name = '')
    {
        return mb_strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    }
}
