<?php

/**
 * Class pocketlistsSettingsSaveController
 */
class pocketlistsSettingsSaveController extends pocketlistsJsonController
{
    public function execute()
    {
        $this->response = (new pocketlistsSettings())->updateSettings(
            waRequest::post('settings', [], waRequest::TYPE_ARRAY)
        );
    }
}
