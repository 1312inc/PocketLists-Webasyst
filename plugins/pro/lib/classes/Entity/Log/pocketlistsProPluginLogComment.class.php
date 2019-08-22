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
                return sprintf_wp('%s commented', $this->getActor()->getName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the comment', $this->getActor()->getName());

            default:
                return sprintf_wp('%s did something with comment', $this->getActor()->getName());
        }
    }
}
