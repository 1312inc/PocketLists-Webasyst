<?php

/**
 * Class pocketlistsAttachment
 */
class pocketlistsAttachment extends pocketlistsEntity
{
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
    private $filetype = '';

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
}
