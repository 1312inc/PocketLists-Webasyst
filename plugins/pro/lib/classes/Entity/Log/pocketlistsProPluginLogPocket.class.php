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
        $name = $this->log->getContact()->getName();

        return sprintf_wp('%s updated the pocket', $name);
    }
}
