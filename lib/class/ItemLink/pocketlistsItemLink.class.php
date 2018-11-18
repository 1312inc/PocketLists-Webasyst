<?php

/**
 * Class pocketlistsItemLink
 */
abstract class pocketlistsItemLink
{
    /**
     * @var pocketlistsItemLinkModel
     */
    protected $itemLinkModel;

    /**
     * @var waSmarty3View
     */
    protected $view;

    /**
     * @return string
     */
    abstract public function getApp();

    /**
     * @return waSmarty3View
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = new waSmarty3View(wa());
        }

        return $this->view;
    }

    /**
     * @param pocketlistsItemLinkModel $itemLinkModel
     *
     * @return $this
     */
    public function setItemLinkModel(pocketlistsItemLinkModel $itemLinkModel)
    {
        $this->itemLinkModel = $itemLinkModel;

        return $this;
    }

    /**
     * @return pocketlistsItemLinkModel
     */
    public function getItemLinkModel()
    {
        return $this->itemLinkModel;
    }


    /**
     * pocketlistsItemLink constructor.
     */
    public function __construct()
    {
        wa($this->getApp());
    }
}
