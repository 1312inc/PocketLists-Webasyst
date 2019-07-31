<?php

/**
 * Class pocketlistsProPluginSettingsActions
 */
class pocketlistsProPluginSettingsActions extends pocketlistsViewActions
{
    public function statusesAction()
    {
        $this->view->assign(
            [
                'labels_json' => json_encode(
                    array_reverse(pl2()->getModel(pocketlistsProPluginLabel::class)->getAllWithSort()),
                    JSON_UNESCAPED_UNICODE
                ),
            ]
        );
    }

    public function shopscriptAction()
    {
        $this->view->assign('params', 'shopscript');
    }

    public function templatesAction()
    {
        $this->view->assign('params', 'templates');
    }
}
