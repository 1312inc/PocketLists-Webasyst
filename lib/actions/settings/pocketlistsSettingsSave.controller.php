<?php

class pocketlistsSettingsSaveController extends  waJsonController
{
    public function execute()
    {
//        if (!pocketlistsHelper::isAdmin()) {
//            throw new waException('Access denied.', 403);
//        }

        $us = new pocketlistsUserSettings();

        // zero settings which are not in POST
        $data = array_merge($us->getZeroSettings(), waRequest::post());

        // update new
        foreach($data as $name => $value) {
            $us->set( $name, $value);
        }
        $this->response = 'ok';
    }
}