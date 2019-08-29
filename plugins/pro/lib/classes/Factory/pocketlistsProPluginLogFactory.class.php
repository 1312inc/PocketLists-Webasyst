<?php

/**
 * Class pocketlistsProPluginLogFactory
 */
class pocketlistsProPluginLogFactory
{
    /**
     * @param pocketlistsLog $log
     *
     * @return pocketlistsProPluginLogComment|pocketlistsProPluginLogItem|pocketlistsProPluginLogList|pocketlistsProPluginLogPocket|pocketlistsProPluginLogAttachment
     * @throws pocketlistsLogicException
     */
    public static function createFromLog(pocketlistsLog $log)
    {
        switch ($log->getEntityType()) {
            case pocketlistsLog::ENTITY_ITEM:
                return new pocketlistsProPluginLogItem($log);

            case pocketlistsLog::ENTITY_LIST:
                return new pocketlistsProPluginLogList($log);

            case pocketlistsLog::ENTITY_COMMENT:
                return new pocketlistsProPluginLogComment($log);

            case pocketlistsLog::ENTITY_POCKET:
                return new pocketlistsProPluginLogPocket($log);

            case pocketlistsLog::ENTITY_ATTACHMENT:
                return new pocketlistsProPluginLogAttachment($log);
        }

        throw new pocketlistsLogicException('unknown log entity');
    }
}
