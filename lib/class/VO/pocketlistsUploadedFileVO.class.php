<?php

/**
 * Class pocketlistsUploadedFileVO
 */
class pocketlistsUploadedFileVO
{
    const PATH = 'attachments';

    /**
     * @var bool
     */
    private $temp = true;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $ext;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int|null
     */
    private $itemId;

    /**
     * @var waRequestFile
     */
    private $file;

    /**
     * @var string
     */
    private $relPath = self::PATH;

    /**
     * @var int
     */
    private $identifier = 0;

    /**
     * pocketlistsUploadedFileVO constructor.
     */
    public function __construct()
    {
        $this->identifier = wa()->getUser()->getId();
    }

    /**
     * @param waRequestFile $file
     *
     * @return static
     */
    public static function createFromWaRequestFile(waRequestFile $file)
    {
        $self = new static();
        $self->name = $file->name;
        $self->ext = $file->extension;
        $self->type = $file->type;
        $self->size = $file->size;
        $self->file = $file;

        return $self;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public static function createTempFromName($name)
    {
        $self = new static();

        $self->name = $name;
        $info = new SplFileInfo($self->getPath());
        try {
            if (waImage::factory($self->getFullPath()) instanceof waImage) {
                $self->type = 'image';
            }
        } catch (waException $e) {
        }
        $self->ext = $info->getExtension();
        $self->size = $info->getSize();

        return $self;
    }

    /**
     * @param int $identifier
     *
     * @return pocketlistsUploadedFileVO
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTemp()
    {
        return $this->temp;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return pocketlistsUploadedFileVO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param int|null $itemId
     *
     * @return pocketlistsUploadedFileVO
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        $this->identifier = $itemId;
        $this->temp = !(bool)$itemId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->itemId
            ? wa()->getDataPath($this->getRelPath(), true, pocketlistsHelper::APP_ID)
            : wa()->getAppCachePath($this->getRelPath(), pocketlistsHelper::APP_ID);
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return $this->getPath().DIRECTORY_SEPARATOR.$this->name;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return waRequestFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getRelPath()
    {
        return sprintf('%s/%s', $this->relPath, $this->identifier);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = sprintf('%s/%s', $this->getRelPath(), $this->name);

        return $this->itemId
            ? wa()->getDataUrl($url, true, pocketlistsHelper::APP_ID)
            : wa()->getAppCachePath($url, pocketlistsHelper::APP_ID);
    }
}
