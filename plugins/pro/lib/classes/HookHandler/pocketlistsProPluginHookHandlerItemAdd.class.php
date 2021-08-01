<?php

/**
 * Class pocketlistsProPluginHookHandlerItemAdd
 */
class pocketlistsProPluginHookHandlerItemAdd extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|pocketlistsEvent $event
     *
     * @return string
     * @throws waException
     */
    public function handle($event = null)
    {
        /** @var pocketlistsProPluginLabelFactory $factoryLabel */
        $factoryLabel = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
        /** @var pocketlistsProPluginShortcutFactory $factoryShortcut */
        $factoryShortcut = pl2()->getEntityFactory(pocketlistsProPluginShortcut::class);

        $label = null;
        $item = $event->getObject();
        $eventParams = $event->getParams();
        if ($item instanceof pocketlistsItem && $item->getId()) {
            $label = $factoryLabel->findForItem($item);
        }
        $label = $label ?: new pocketlistsProPluginLabel();
        $labels = $factoryLabel->findAll();
        $shortcutsGroups = $factoryShortcut->findAllGrouped();
        $isNew = (int) !($item instanceof pocketlistsItem && $item->getId());

        $this->getView()->assign(
            [
                'pl2pro' => [
                    'itemLabel' => $label,
                    'labels' => $labels,
                    'shortcutsGrouped' => $shortcutsGroups,
                    'isNew' => $isNew,
                    'shortcutsExists' => (int) ($shortcutsGroups && $isNew),
                    'labelsExists' => (int) $labels,

                    'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
                    'external' => !empty($eventParams['external']),
                    'externalApp' => $eventParams['externalApp'] ?? null,
                ],
            ]
        );

        return $this->getView()->fetch($this->getViewTemplate('backend_item_add', $eventParams['externalApp'] ?? null));
    }
}
