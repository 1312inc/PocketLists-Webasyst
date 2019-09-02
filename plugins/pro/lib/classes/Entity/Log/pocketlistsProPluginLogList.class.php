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
        $params = $this->log->getParams();

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
                return sprintf_wp('%s created the list', $this->log->getContact()->getName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the list', $this->log->getContact()->getName());

            case pocketlistsLog::ACTION_UPDATE:
                return sprintf_wp('%s updated list details', $this->log->getContact()->getName());

            default:
                return sprintf_wp('%s did something with list', $this->log->getContact()->getName());

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
