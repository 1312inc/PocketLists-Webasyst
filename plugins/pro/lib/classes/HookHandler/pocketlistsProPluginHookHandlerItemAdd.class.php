<?php

/**
 * Class pocketlistsProPluginHookHandlerItemAdd
 */
class pocketlistsProPluginHookHandlerItemAdd extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|pocketlistsItem $item
     *
     * @return array
     * @throws waException
     */
    public function handle($item = null)
    {
        $return = [];

        /** @var pocketlistsProPluginLabelFactory $factoryLabel */
        $factoryLabel = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
        /** @var pocketlistsProPluginShortcutFactory $factoryShortcut */
        $factoryShortcut = pl2()->getEntityFactory(pocketlistsProPluginShortcut::class);

        $label = null;
        if ($item instanceof pocketlistsItem && $item->getId()) {
            $label = $factoryLabel->findForItem($item);
        }
        $label = $label ?: new pocketlistsProPluginLabel();
        $labels = $factoryLabel->findAll();
        $shortcutsGroups = $factoryShortcut->findAllGrouped();
        $isNew = (int)!($item instanceof pocketlistsItem && $item->getId());

        $this->getView()->assign(
            [
                'pl2pro' => [
                    'itemLabel'        => $label,
                    'labels'           => $labels,
                    'shortcutsGrouped' => $shortcutsGroups,
                    'isNew'            => $isNew,
                    'shortcutsExists'  => (int)($shortcutsGroups && $isNew),
                    'labelsExists'     => (int)$labels,
                ],
            ]
        );

        $return['compact'] = $this->getView()->fetch($this->getViewTemplate('backend_item_add'));
        $return['detail'] = $return['compact'];

        return $return;
    }
}
