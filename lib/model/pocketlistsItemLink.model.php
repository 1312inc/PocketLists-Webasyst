<?php

/**
 * Class pocketlistsItemLinkModel
 *
 * @property int    $item_id
 * @property string $app
 * @property string $entity_type
 * @property int    $entity_id
 * @property string $data
 */
class pocketlistsItemLinkModel extends kmModelExt
{
    protected $table = 'pocketlists_item_link';

    /**
     * @var pocketlistsItemLinkInterface
     */
    protected $linkedClass;

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
     * @param string $app
     * @param string $entityId
     *
     * @return null|pocketlistsItemLinkModel|pocketlistsItemLinkModel[]
     */
    public function getLinkedItems($app, $entityId)
    {
        return $this->findByFields(['app' => $app, 'entity_id' => $entityId]);
    }

    /**
     * @return string
     */
    public function renderAutocomplete()
    {
        $template = wa()->getAppPath(
            sprintf('templates/include/item_linked_entities/%s.%s.autocomplete.html', $this->app, $this->entity_type),
            pocketlistsHelper::APP_ID
        );

//        $render = wa()->event('item.render_autocomplete', $this);

        if (file_exists($template)) {
            $this->getView()->clearAllAssign();
            $this->getView()->assign('link', $this);

            $render = $this->getView()->fetch($template);
        } else {
            $render = (string)$this;
        }

        return $render;
    }

    /**
     * @return string
     */
    public function renderPreview()
    {
        if (!$this->entity_id || !$this->entity_type) {
            return '';
        }

        $template = wa()->getAppPath(
            sprintf('templates/include/item_linked_entities/%s.%s.preview.html', $this->app, $this->entity_type),
            pocketlistsHelper::APP_ID
        );

        $pluginRender = wa()->event('item.render_linked', $this);
        $render = !empty($pluginRender['preview']) ? $pluginRender['preview'] : '';

        if ($this->getEntityClass()->isEnabled() && !$render && file_exists($template)) {
            if (!$this->getEntityClass()->getEntity()) {
                return '';
            }

            $this->getView()->clearAllAssign();
            $vars = [
                'link'  => $this,
                'extra' => $this->getEntityClass()->getExtraData(),
            ];
            $this->getView()->assign($vars);

            $render = $this->getView()->fetch($template);
        }

        return $render;
    }

    /**
     * @return pocketlistsItemLinkInterface
     * @throws waException
     */
    public function getEntityClass()
    {
        if ($this->linkedClass === null) {
            $app = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($this->app);
            $this->linkedClass = $app;
            $this->linkedClass->setItemLinkModel($this);
        }

        return $this->linkedClass;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ucfirst($this->entity_type).' #'.$this->entity_id;
    }
}
