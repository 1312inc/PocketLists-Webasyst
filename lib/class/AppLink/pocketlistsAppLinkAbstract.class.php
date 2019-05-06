<?php

/**
 * Class pocketlistsAppLinkAbstract
 */
abstract class pocketlistsAppLinkAbstract implements pocketlistsAppLinkInterface
{
    const LIMIT = 10;

    protected $enabled = null;

    /**
     * @var waSmarty3View
     */
    protected $view;

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
     * pocketlistsItemLink constructor.
     */
    public function __construct()
    {
        try {
            wa($this->getApp());
        } catch (waException $ex) {
            $this->enabled = false;
        }
    }

    /**
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countItems()
    {
        return pl2()->getModel(pocketlistsItem::class)->getCountForApp($this->getApp());
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->enabled === null) {
            $this->enabled = wa()->appExists($this->getApp());
        }

        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getBannerHtml()
    {
       $template = wa()->getAppPath(
            sprintf('templates/include/item_linked_entities/%s.banner.html', $this->getApp()),
            pocketlistsHelper::APP_ID
        );

        $render = '';

        if ($this->isEnabled() && file_exists($template)) {
            $this->getView()->assign('app', $this);
            $render = $this->getView()->fetch($template);
        }

        return $render;
    }
}
