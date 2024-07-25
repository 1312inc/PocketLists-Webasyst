<?php


/**
 * Class pocketlistsLogService
 */
class pocketlistsLogService
{
    /**
     * @var pocketlistsLogFactory
     */
    private $factory;

    /**
     * pocketlistsLogService constructor.
     *
     * @throws waException
     */
    public function __construct()
    {
        $this->factory = pl2()->getEntityFactory(pocketlistsLog::class);
    }

    /**
     * @return pocketlistsLogFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param pocketlistsLog $log
     *
     * @return bool|waDbResultUpdate|null
     * @throws waException
     */
    public function add(pocketlistsLog $log)
    {
        $ok = $this->factory->save($log);

        if ($ok) {
            pl2()->getEventDispatcher()->dispatch(
                new pocketlistsEvent(
                    pocketlistsEventStorage::LOG_INSERT,
                    $log
                )
            );

            pocketlistsWebSoket::getInstance()->sendWebsocketData([
                'id'                  => $log->getId(),
                'action'              => $log->getAction(),
                'entity_type'         => $log->getEntityType(),
                'contact_id'          => $log->getContactId(),
                'pocket_id'           => $log->getPocketId(),
                'list_id'             => $log->getListId(),
                'item_id'             => $log->getItemId(),
                'comment_id'          => $log->getCommentId(),
                'attachment_id'       => $log->getAttachmentId(),
                'assigned_contact_id' => $log-> getAssignedContactId(),
                'params'              => $log->getParams()
            ]);
        }

        return $ok;
    }

    /**
     * @param pocketlistsLog $log
     */
    public function explain(pocketlistsLog $log)
    {

    }
}
