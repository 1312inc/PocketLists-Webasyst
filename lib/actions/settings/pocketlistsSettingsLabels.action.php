<?php

/**
 * Class pocketlistsSettingsLabelsAction
 */
class pocketlistsSettingsLabelsAction extends pocketlistsViewAction
{
    public function runAction($params = null)
    {
        $labels = pl2()->getModel(pocketlistsLabel::class)->getAllWithSort();

        $this->view->assign([
            'labels_json' => json_encode(array_reverse($labels), JSON_UNESCAPED_UNICODE),
        ]);
    }
}
