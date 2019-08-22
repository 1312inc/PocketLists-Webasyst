<?php

/**
 * Class pocketlistsProPluginLogItem
 */
class pocketlistsProPluginLogItem extends pocketlistsProPluginLogAbstract
{
    /**
     * @return string
     */
    public function getLogEntry()
    {
        $params = $this->log->getParams();

        return ifset($params, 'item', 'name', 'no saved item name');
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s created the to-do', $this->getActor()->getName());

            case pocketlistsLog::ACTION_COMPLETE:
                return sprintf_wp('%s completed the to-do', $this->getActor()->getName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the to-do', $this->getActor()->getName());

            case pocketlistsLog::ACTION_EDIT:
                return sprintf_wp('%s edited the to-do', $this->getActor()->getName());

            default:
                return sprintf_wp('%s did something with to-do', $this->getActor()->getName());

        }
    }
}
