<?php

class pocketlistsRecapCli extends waCliController
{
    public function run($params = null)
    {
        $test = waRequest::param('test', false, waRequest::TYPE_INT);

        $time = time();
        $asp = new waAppSettingsModel();
        $asp->set('pocketlists', 'last_recap_cron_time', $time);

        pocketlistsNotifications::notifyDailyRecap(array(), $test);
    }
}
