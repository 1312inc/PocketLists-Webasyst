<?php

/**
 * Class pocketlistsShortcutRenewController
 */
class pocketlistsShortcutRenewController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $group = waRequest::request('group', 0, waRequest::TYPE_INT);
        $shortcuts = waRequest::request('shortcuts', '', waRequest::TYPE_STRING_TRIM);

        $shortcuts = explode(',', $shortcuts);

        /** @var pocketlistsShortcutFactory $shortcutFactory */
        $shortcutFactory = pl2()->getEntityFactory(pocketlistsShortcut::class);
        /** @var pocketlistsShortcutModel $shortcutModel */
        $shortcutModel= $shortcutFactory->getModel();

        $existingShortcuts = array_flip(
            array_column($shortcutModel->getByGroup($group), 'name')
        );

        if (!$group) {
            $group = $shortcutModel->getMaxGroup() + 1;
        }

        foreach ($shortcuts as $shortcut) {
            if (trim($shortcut) === '') {
                continue;
            }

            if (!array_key_exists($shortcut, $existingShortcuts)) {
                /** @var pocketlistsShortcut $shrtct */
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

        $shortcutModel->renewGroupNums();
    }
}
