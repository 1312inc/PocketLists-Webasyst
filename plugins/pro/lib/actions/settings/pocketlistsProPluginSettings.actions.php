<?php

/**
 * Class pocketlistsProPluginSettingsActions
 */
class pocketlistsProPluginSettingsActions extends pocketlistsViewActions
{
    public function labelsAction()
    {
        $labels = pl2()->getModel(pocketlistsProPluginLabel::class)->getAllWithSort();
        $this->view->assign(
            [
                'labels_json' => json_encode(array_reverse($labels), JSON_UNESCAPED_UNICODE),
            ]
        );
    }

    public function shopscriptAction()
    {
        $automation = pocketlistsProPlugin::getInstance()->getAutomationService();

        /** @var shopWorkflowState[] $shopStates */
        $shopStates = $automation->getAvailableEventsForGroup();

        $this->view->assign(
            ['params' => 'shopscript', 'shopActions' => $shopStates]
        );
    }

    public function shortcutsAction()
    {
        $shortcuts = pl2()->getModel(pocketlistsProPluginShortcut::class)->getAllGrouped();
        $this->view->assign(
            [
                'shortcuts_json' => json_encode($shortcuts, JSON_UNESCAPED_UNICODE),
            ]
        );
    }
}
