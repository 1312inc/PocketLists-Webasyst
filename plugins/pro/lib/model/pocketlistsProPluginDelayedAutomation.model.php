<?php

/**
 * Class pocketlistsProPluginDelayedAutomationModel
 */
class pocketlistsProPluginDelayedAutomationModel extends pocketlistsModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_pro_delayed_automation';

    /**
     * @param string $time
     *
     * @return array
     */
    public function getNewByDelayedTime($time)
    {
        return $this->select('*')
            ->where('status = ? and apply_datetime < ?', pocketlistsProPluginDelayedAutomation::STATUS_NEW, $time)
            ->fetchAll();
    }
}
