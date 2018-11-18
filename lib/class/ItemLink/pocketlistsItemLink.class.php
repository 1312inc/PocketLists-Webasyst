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

    abstract function getApp();

    /**
     * @return string
     */
    public function renderAutocompleteItemLink()
    {
        $link = $this->getItemLinkModel();
        $template = wa()->getAppPath(
            sprintf('templates/include/item_linked_entities/autocomplete/%s.%s.html', $link->app, $link->entity_type),
            pocketlistsHelper::APP_ID
        );

//        $render = wa()->event('item.render_autocomplete', $this);

        if (file_exists($template)) {
            $this->getView()->clearAllAssign();
            $this->getView()->assign('link', $link);

            $render = $this->getView()->fetch($template);
        } else {
            $render = (string)$link;
        }

        return $render;
    }

    /**
     * @return string
     */
    public function renderPreviewItemLink()
    {
        $link = $this->getItemLinkModel();
        $template = wa()->getAppPath(
            sprintf('templates/include/item_linked_entities/%s.%s.html', $link->app, $link->entity_type),
            pocketlistsHelper::APP_ID
        );

        $render = wa()->event('item.render_linked', $link);

        if (!$render && file_exists($template)) {
            $this->getView()->clearAllAssign();
            $this->getView()->assign('link', $link);

            $render = $this->getView()->fetch($template);
        }

        return $render;
    }

}
