<?php

/**
 * Class pocketlistsDebugResetController
 */
class pocketlistsDebugResetController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new waException('Access denied.', 403);
        }

        $appPath = wa()->getConfig()->getAppPath();

        $model = new waModel();
        $db = require $appPath.'/lib/config/db.php';

        foreach ($db as $table => $meta) {
            $model->exec('truncate '.$table);
        }

        (new waContactRightsModel())->deleteByField('app_id', 'pocketlists');
        (new waAppSettingsModel())->deleteByField('app_id', 'pocketlists');
        (new waContactSettingsModel())->deleteByField('app_id', 'pocketlists');

        (new webasystSettingsClearCacheController())->flushCache();

        $this->response = 'ok';
    }
}
