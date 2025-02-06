<?php

/**
 * Class pocketlistsDelayedAutomationModel
 */
class pocketlistsDelayedAutomationModel extends pocketlistsModel
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
            ->where('status = ? and apply_datetime < ?', pocketlistsDelayedAutomation::STATUS_NEW, $time)
            ->fetchAll();
    }
}