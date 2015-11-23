<?php

class pocketlistsSettingsSaveController extends  waJsonController
{
    public function execute()
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();
//        $settings = $sm->get($app_name);

        $cs->set(wa()->getUser()->getId(), $app_name, 'app_icon', json_encode(waRequest::post('app_icon', 1, waRequest::TYPE_INT)));
        $cs->set(wa()->getUser()->getId(), $app_name, 'email_me', json_encode(waRequest::post('email_me', array(), waRequest::TYPE_ARRAY)));
        $cs->set(wa()->getUser()->getId(), $app_name, 'daily_recap', json_encode(waRequest::post('daily_recap', array(), waRequest::TYPE_ARRAY)));

        $this->response = 'ok';
    }
}