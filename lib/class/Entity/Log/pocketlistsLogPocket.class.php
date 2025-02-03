<?php

/**
 * Class pocketlistsLogPocket
 */
class pocketlistsLogPocket extends pocketlistsLogAbstract
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
        return sprintf_wp('%s updated the pocket', $this->getActorName());
    }
}
