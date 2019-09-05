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
        $name = $this->log->getContact()->getName();

        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s commented', $name);

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the comment', $name);

            default:
                return sprintf_wp('%s updated the comment', $name);
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
