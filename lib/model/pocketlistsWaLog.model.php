<?php

/**
 * Class pocketlistsWaLogModel
 */
class pocketlistsWaLogModel extends waLogModel
{
    /**
     * @param array $where
     * @return array
     */
    public function getLogs($where = array())
    {
        $where = array('app_id' => pocketlistsHelper::APP_ID) + $where;
        return parent::getLogs($where);
    }

    /**
     * @param array $where
     * @return array
     */
    public function getAllLogs()
    {
        return $this->getLastLogs()->fetchAll();
    }

    /**
     * @param string $datetime
     * @return waDbQuery
     */
    public function getLastLogs($datetime = '')
    {
        return $this
            ->select("*")
            ->where("action != 'login' AND action != 'logout'
                    AND app_id = '{$this->escape(pocketlistsHelper::APP_ID)}'
                    AND datetime > '{$this->escape($datetime)}'")
            ->order("id DESC");
    }
}
