<?php

/**
 * Class pocketlistsProPluginItemEventListener
 */
class pocketlistsProPluginItemEventListener
{
    /**
     * @var pocketlistsProPluginLabelFactory
     */
    private $labelFactory;

    /**
     * pocketlistsProPluginItemEventListener constructor.
     *
     * @throws waException
     */
    public function __construct()
    {
        $this->labelFactory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
    }

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

        try {
            $labelId = $item->getDataField('pro_label');
            /** @var pocketlistsProPluginLabel $label */
            $label = $this->labelFactory->findById($labelId);

            if ($label instanceof pocketlistsProPluginLabel) {
                return sprintf(
                    '<a href="#" class="pl-label" style="background-color: #%s">%s</a>',
                    $label->getColor(),
                    htmlspecialchars($label->getName())
                );
            }
        } catch (waException $ex) {}

        return '';
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
