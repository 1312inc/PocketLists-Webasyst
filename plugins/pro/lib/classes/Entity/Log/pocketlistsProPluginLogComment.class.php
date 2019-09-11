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
        $params = $this->log->getParamsArray();

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
                return sprintf_wp('%s commented', $this->getActorName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the comment', $this->getActorName());

            default:
                return sprintf_wp('%s updated the comment', $this->getActorName());
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
