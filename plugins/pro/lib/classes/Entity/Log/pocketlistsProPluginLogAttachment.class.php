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
        $name = $this->log->getContact()->getName();

        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s added an attachment', $name);

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the attachment', $name);

            default:
                return sprintf_wp('%s updated the attachment', $name);
        }
    }
}
