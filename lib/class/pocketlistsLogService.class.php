<?php


/**
 * Class pocketlistsLogService
 */
class pocketlistsLogService
{
    /**
     * @param pocketlistsLog $log
     *
     * @return bool|waDbResultUpdate|null
     * @throws waException
     */
    public function add(pocketlistsLog $log)
    {
        return pl2()->getEntityFactory(pocketlistsLog::class)->save($log);
    }

    /**
     * @param pocketlistsLog $log
     */
    public function explain(pocketlistsLog $log)
    {

    }
}
