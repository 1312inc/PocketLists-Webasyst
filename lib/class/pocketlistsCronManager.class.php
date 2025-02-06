<?php

/**
 * Class pocketlistsCronManager
 */
class pocketlistsCronManager
{
    const APPLY_DELAYED_AUTOMATIONS = 'apply_delayed_automations';

    /**
     * @var array
     */
    private $cronJobs;

    /**
     * @var waAppSettingsModel
     */
    private $settingsModel;

    /**
     * pocketlistsCronManager constructor.
     */
    public function __construct()
    {
        $this->settingsModel = new waAppSettingsModel();
    }

    /**
     * @param null $name
     *
     * @return array|string
     */
    public function getCronJobs($name = null)
    {
        if (!is_array($this->cronJobs)) {
            $this->cronJobs = [];
            $path = wa()->getAppPath('lib/config/cron.php');
            if (file_exists($path)) {
                $this->cronJobs = include($path);
            }
        }

        return $name ? (isset($this->cronJobs[$name]) ? $this->cronJobs[$name] : '') : $this->cronJobs;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function saveLastRunCronJob($name)
    {
        return $this->settingsModel->set(pocketlistsHelper::ID_ARR, 'cron_job.'.$name, date('Y-m-d H:i:s'));
    }

    /**
     * @param string $name
     *
     * @return bool|DateTime
     * @throws Exception
     */
    public function getLastRunCronJob($name)
    {
        $date = $this->settingsModel->get(pocketlistsHelper::ID_ARR, 'cron_job.'.$name, null);

        if (!$date) {
            return false;
        }

        return new DateTime($date);
    }
}
