<?php

/**
 * Class pocketlistsProPluginLogList
 */
class pocketlistsProPluginLogList extends pocketlistsProPluginLogAbstract
{
    /**
     * @return string
     */
    public function getLogEntry()
    {
        $params = $this->log->getParamsArray();

        return ifset($params, 'list', 'name', 'no saved list name');
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s created the list', $this->getActorName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the list', $this->getActorName());

            case pocketlistsLog::ACTION_UPDATE:
                return sprintf_wp('%s updated list details', $this->getActorName());

            default:
                return sprintf_wp('%s updated the list', $this->getActorName());

        }
    }

    /**
     * @return string
     */
    public function getGlyph()
    {
        return 'list';
    }
}
