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
        $name = $this->log->getContact()->getName();

        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s created the list', $name);

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the list', $name);

            case pocketlistsLog::ACTION_UPDATE:
                return sprintf_wp('%s updated list details', $name);

            default:
                return sprintf_wp('%s did something with list', $name);

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
