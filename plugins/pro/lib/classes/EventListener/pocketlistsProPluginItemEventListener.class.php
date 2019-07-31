<?php

/**
 * Class pocketlistsProPluginItemEventListener
 */
class pocketlistsProPluginItemEventListener
{
    /**
     * @param pocketlistsEventInterface $event
     */
    public function onSave(pocketlistsEventInterface $event)
    {
        /** @var pocketlistsItem $item */
        $item = $event->getObject();

        if (!$item instanceof pocketlistsItem) {
            return;
        }
    }

    /**
     * @param pocketlistsEventInterface $event
     */
    public function onDelete(pocketlistsEventInterface $event)
    {
        /** @var pocketlistsItem $item */
        $item = $event->getObject();

        if (!$item instanceof pocketlistsItem) {
            return;
        }
    }

    /**
     * @param pocketlistsEventInterface $event
     *
     * @return string
     */
    public function getLabel(pocketlistsEventInterface $event)
    {
        $item = $this->getItemFromEvent($event);

        if (!$item) {
            return '';
        }

        return '<a href="#" class="pl-label pl-dark-purple">STATUS</a>';
    }

    /**
     * @param pocketlistsEventInterface $event
     *
     * @return bool|pocketlistsItem
     */
    private function getItemFromEvent(pocketlistsEventInterface $event)
    {
        /** @var pocketlistsItem $item */
        $item = $event->getObject();

        if (!$item instanceof pocketlistsItem) {
            return false;
        }

        return $item;
    }
}
