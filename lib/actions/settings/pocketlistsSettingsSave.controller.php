<?php

class pocketlistsSettingsSaveController extends waJsonController
{
    public function execute()
    {
//        if (!pocketlistsRBAC::isAdmin()) {
//            throw new waException('Access denied.', 403);
//        }

        $us = new pocketlistsUserSettings();

        // zero settings which are not in POST
        $data = array_merge($us->getZeroSettings(), waRequest::post());

        // update new
        foreach ($data as $name => $value) {
            $us->set($name, $value);
        }

        // flush cache
        $cache = new waVarExportCache(pocketlistsHelper::APP_ID . '_todoItems' . wa()->getUser()->getId());
        $cache->delete();

        $this->response = 'ok';
    }
}
