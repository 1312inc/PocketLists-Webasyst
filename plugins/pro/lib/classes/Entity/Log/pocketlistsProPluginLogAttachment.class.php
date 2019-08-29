<?php

/**
 * Class pocketlistsProPluginLogAttachment
 */
class pocketlistsProPluginLogAttachment extends pocketlistsProPluginLogAbstract
{
    /**
     * @return string
     */
    public function getLogEntry()
    {
        $params = $this->log->getParams();

        return ifset($params, 'attachment', 'filename', 'no attachment name');
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s added attachment', $this->getActor()->getName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted attachment', $this->getActor()->getName());

            default:
                return sprintf_wp('%s did something with attachment', $this->getActor()->getName());
        }
    }
}
