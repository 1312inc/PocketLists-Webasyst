<?php

/**
 * Class pocketlistsProPluginLogFactory
 */
class pocketlistsProPluginLogFactory extends pocketlistsLogFactory
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

    /**
     * @param pocketlistsLogContext     $context
     * @param pocketlistsProPluginLabel $label
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     * @throws waException
     */
    public function createNewAfterAddLabel(pocketlistsLogContext $context, pocketlistsProPluginLabel $label)
    {
        $log = $this->createNewAfterItemUpdate($context);

        $log->setParamsArray(
            array_merge(
                [
                    'item_action' => 'add_label',
                    'label'       =>
                        [
                            'name'  => $label->getName(),
                            'color' => $label->getColor(),
                        ],
                ],
                $log->getParamsArray()
            )
        );

        return $log;
    }
}
