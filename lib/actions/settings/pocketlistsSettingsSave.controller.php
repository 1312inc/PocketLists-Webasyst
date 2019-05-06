<?php

/**
 * Class pocketlistsSettingsSaveController
 */
class pocketlistsSettingsSaveController extends pocketlistsJsonController
{
    public function execute()
    {
        $us = $this->user->getSettings();

        // zero settings which are not in POST
        $data = array_merge($us->getZeroSettings(), waRequest::post());

        // update new
        foreach ($data as $name => $value) {
            $us->set($name, $value);
        }

        $this->response = 'ok';
    }
}
