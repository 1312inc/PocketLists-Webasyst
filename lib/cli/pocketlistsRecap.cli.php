<?php

class pocketlistsRecapCli extends waCliController
{
    public function run($params = null)
    {
        $time = time();
        $asp = new waAppSettingsModel();
        $asp->set('pocketlists', 'last_recap_cron_time', $time);

        pocketlistsNotifications::notifyDailyRecap();
    }
}