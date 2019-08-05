<?php

/**
 * Class pocketlistsProPluginShortcutAutocompleteController
 */
class pocketlistsProPluginShortcutAutocompleteController extends pocketlistsJsonController
{
    const TYPE_SETTINGS = 'settings';
    const LIMIT = 10;

    /**
     * @throws waException
     */
    public function execute()
    {
        $type = waRequest::request('type', self::TYPE_SETTINGS, waRequest::TYPE_STRING_TRIM);
        $term = waRequest::request('term', '', waRequest::TYPE_STRING_TRIM);

        /** @var pocketlistsProPluginShortcutModel $shortcutModel */
        $shortcutModel = pl2()->getModel(pocketlistsProPluginShortcut::class);
        switch ($type) {
            case self::TYPE_SETTINGS:
            default:
                $shortcuts = $shortcutModel->getByTerm($term, self::LIMIT);
        }

        foreach ($shortcuts as $shortcut) {
            $this->response[] = [
                'value' => $shortcut['name'],
                'label' => htmlspecialchars($shortcut['name'])
            ];
        }
    }
}
