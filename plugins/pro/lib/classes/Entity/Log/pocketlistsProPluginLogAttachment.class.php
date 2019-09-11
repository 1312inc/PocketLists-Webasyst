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
        $params = $this->log->getParamsArray();

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
                return sprintf_wp('%s added an attachment', $this->getActorName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the attachment', $this->getActorName());

            default:
                return sprintf_wp('%s updated the attachment', $this->getActorName());
        }
    }
}
