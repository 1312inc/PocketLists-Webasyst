<?php

class pocketlistsSettingsSaveController extends  waJsonController
{
    public function execute()
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();

        $data = waRequest::post();
        foreach($data as $name => $value) {
            $cs->set(wa()->getUser()->getId(), $app_name, $name, $value);
        }
        $this->response = 'ok';
    }
}