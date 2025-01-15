<?php

/**
 * Class pocketlistsAttachment
 */
class pocketlistsAttachment extends pocketlistsEntity
{
    const PREVIEW_SIZE = 600;
    const TYPE_IMAGE = 'image';

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

        return $this;
    }

    /**
     * @return string
     */
    public function getFiletype()
    {
        return $this->filetype;
    }

    /**
     * @param string $filetype
     *
     * @return pocketlistsAttachment
     */
    public function setFiletype($filetype)
    {
        $this->filetype = $filetype;

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
     * @param $attach_id
     * @return string|null
     * @throws waException
     */
    public static function getUrl($attach_id)
    {
        static $url;
        if (empty($attach_id)) {
            return null;
        } elseif (!isset($url)) {
            $url = wa()->getUrl(true).wa()->getConfig()->getBackendUrl().'/'.pocketlistsHelper::APP_ID.'/download/%s';
        }

        return sprintf($url, $attach_id);
    }
}
