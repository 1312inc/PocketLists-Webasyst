<?php

/**
 * Class pocketlistsProPluginLogPocket
 */
class pocketlistsProPluginLogPocket extends pocketlistsProPluginLogAbstract
{
    /**
     * @return string
     */
    public function getLogEntry()
    {
        return 'pocket';
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        switch ($this->log->getAction()) {
            default:
                return sprintf_wp('%s did something with pocket', $this->log->getContact()->getName());
        }
    }
}
