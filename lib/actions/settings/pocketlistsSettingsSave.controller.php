<?php

class pocketlistsSettingsSaveController extends  waJsonController
{
    public function execute()
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();

//        if (!pocketlistsHelper::isAdmin()) {
//            throw new waException('Access denied.', 403);
//        }

        $data = array_merge(array(
            'daily_recap_on' => 0,
            'email_assign_me' => 0,
            'email_complete_item_on' => 0,
            'email_add_item_on' => 0,
            'email_comment_item_on' => 0,
            'email_create_list_on' => 0,
            'stream_inbox_list' => 0
        ), waRequest::post());
        // update new
        foreach($data as $name => $value) {
            $cs->set(wa()->getUser()->getId(), $app_name, $name, $value);
        }
        $this->response = 'ok';
    }
}