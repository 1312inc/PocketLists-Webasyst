<?php

class pocketlistsProPluginSettingsSaveController extends pocketlistsJsonController
{
    public function execute()
    {
        $this->response = (new pocketlistsProPluginSettings())->updateSettings(
            waRequest::post('settings', [], waRequest::TYPE_ARRAY)
        );
    }
}
