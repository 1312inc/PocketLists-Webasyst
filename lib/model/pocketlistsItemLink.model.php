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
    protected static $thisApp;

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
        $template = wa()->getAppPath(
            sprintf('templates/include/item_linked_entities/%s.%s.preview.html', $this->app, $this->entity_type),
            pocketlistsHelper::APP_ID
        );

        $render = wa()->event('item.render_linked', $this);

        if (!$render && file_exists($template)) {
            $this->getView()->clearAllAssign();
            $this->getView()->assign('link', $this);

            $render = $this->getView()->fetch($template);
        }

        return $render;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ucfirst($this->entity_type).' #'.$this->entity_id;
    }
}
