<?php

/**
 * Class pocketlistsDebugSaveController
 */
class pocketlistsDebugSaveController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new waException('Access denied.', 403);
        }

        $linkedAppsConfig = require_once wa()->getConfig()->getLinkedAppConfigPath();
        if (!is_array($linkedAppsConfig)) {
            $linkedAppsConfig = [];
        }

        $newAppsConfig = waRequest::post('apps', [], waRequest::TYPE_ARRAY);

        $config = array_merge($linkedAppsConfig, $newAppsConfig);

        waUtils::varExportToFile($config, wa()->getConfig()->getLinkedAppConfigPath());

        $this->response = 'ok';
    }
}
