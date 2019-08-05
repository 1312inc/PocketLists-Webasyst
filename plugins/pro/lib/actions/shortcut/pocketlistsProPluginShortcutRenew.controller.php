<?php

/**
 * Class pocketlistsProPluginShortcutRenewController
 */
class pocketlistsProPluginShortcutRenewController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $group = waRequest::request('group', 0, waRequest::TYPE_INT);
        $shortcuts = waRequest::request('shortcuts', '', waRequest::TYPE_STRING_TRIM);

        $shortcuts = explode(',', $shortcuts);

        /** @var pocketlistsProPluginShortcutModel $shortcutModel */
        $shortcutModel = pl2()->getModel(pocketlistsProPluginShortcut::class);
        /** @var pocketlistsProPluginShortcutFactory $shortcutFactory */
        $shortcutFactory = pl2()->getEntityFactory(pocketlistsProPluginShortcut::class);

        $existingShortcuts = array_flip(
            array_column($shortcutModel->getByGroup($group), 'name')
        );

        if (!$group) {
            $group = $shortcutModel->getMaxGroup() + 1;
        }

        foreach ($shortcuts as $shortcut) {
            if (!array_key_exists($shortcut, $existingShortcuts)) {
                /** @var pocketlistsProPluginShortcut $shrtct */
                $shrtct = $shortcutFactory->createNew();
                $shrtct
                    ->setName($shortcut)
                    ->setGroup($group);
                $shortcutFactory->insert($shrtct);
            } else {
                unset($existingShortcuts[$shortcut]);
            }
        }

        foreach ($existingShortcuts as $name => $index) {
            $shortcutModel->deleteByNameFromGroup($name, $group);
        }
    }
}
