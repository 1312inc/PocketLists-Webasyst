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
    public function __toString()
    {
        return ucfirst($this->entity_type).' #'.$this->entity_id;
    }
}
