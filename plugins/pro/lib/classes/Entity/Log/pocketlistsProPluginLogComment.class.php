<?php

/**
 * Class pocketlistsProPluginLogComment
 */
class pocketlistsProPluginLogComment extends pocketlistsProPluginLogAbstract
{
    /**
     * @return string
     */
    public function getLogEntry()
    {
        $params = $this->log->getParams();

        return ifset($params, 'comment', 'comment', 'no saved comment');
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s commented', $this->log->getContact()->getName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the comment', $this->log->getContact()->getName());

            default:
                return sprintf_wp('%s did something with comment', $this->log->getContact()->getName());
        }
    }

    /**
     * @return string
     */
    public function getGlyph()
    {
        return 'comment';
    }
}
