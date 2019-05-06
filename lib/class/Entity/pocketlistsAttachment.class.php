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
}
