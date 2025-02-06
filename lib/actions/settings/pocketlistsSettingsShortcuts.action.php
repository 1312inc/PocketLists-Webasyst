<?php

/**
 * Class pocketlistsSettingsShortcutsAction
 */
class pocketlistsSettingsShortcutsAction extends pocketlistsViewAction
{
    public function runAction($params = null)
    {
        $shortcuts = pl2()->getModel(pocketlistsShortcut::class)->getAllGrouped();
        $this->view->assign([
            'shortcuts_json' => json_encode($shortcuts, JSON_UNESCAPED_UNICODE),
        ]);
    }
}
