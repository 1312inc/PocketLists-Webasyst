<?php


/**
 * Class pocketlistsLogService
 */
class pocketlistsLogService
{
    const ENTITIES = [
        pocketlistsLog::ENTITY_USER,
        pocketlistsLog::ENTITY_POCKET,
        pocketlistsLog::ENTITY_LIST,
        pocketlistsLog::ENTITY_ITEM,
        pocketlistsLog::ENTITY_COMMENT,
        pocketlistsLog::ENTITY_LOCATION,
        pocketlistsLog::ENTITY_ATTACHMENT
    ];
    const ACTIONS = [
        pocketlistsLog::ACTION_ADD,
        pocketlistsLog::ACTION_EDIT,
        pocketlistsLog::ACTION_UPDATE,
        pocketlistsLog::ACTION_DELETE,
        pocketlistsLog::ACTION_ASSIGN,
        pocketlistsLog::ACTION_ARCHIVE,
        pocketlistsLog::ACTION_UNARCHIVE,
        pocketlistsLog::ACTION_COMPLETE,
        pocketlistsLog::ACTION_UNCOMPLETE
    ];

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
        }

        return $ok;
    }

    /**
     * @param $entity
     * @param $action
     * @param $logs
     * @return bool
     * @throws waException
     */
    public static function multipleAdd($entity, $action, $logs = [])
    {
        if (
            empty($logs)
            || !is_array($logs)
            || !in_array($entity, self::ENTITIES)
            || !in_array($action, self::ACTIONS)
        ) {
            return false;
        }

        $default = [
            'action'              => $action,
            'entity_type'         => $entity,
            'contact_id'          => pl2()->getUser()->getId(),
            'pocket_id'           => null,
            'list_id'             => null,
            'item_id'             => null,
            'comment_id'          => null,
            'attachment_id'       => null,
            'location_id'         => null,
            'additional_id'       => null,
            'assigned_contact_id' => null,
            'params'              => '',
            'create_datetime'     => date('Y-m-d H:i:s')
        ];

        foreach ($logs as &$_log) {
            $id = ifset($_log, 'id', null);
            unset($_log['id'], $_log['action'], $_log['contact_id']);
            $params = [$entity => $_log];
            unset($_log['create_datetime']);
            $_log = array_intersect_key($_log, $default) + $default;
            $_log['params'] = json_encode($params, JSON_UNESCAPED_UNICODE);
            if ($id) {
                $_log[$entity.'_id'] = $id;
            }
        }
        unset($_log);

        $log_model = pl2()->getModel(pocketlistsLog::class);
        $result = $log_model->multipleInsert($logs);
        if ($result->getResult()) {
            $last_id = $result->lastInsertId();
            $rows_count = $result->affectedRows();
            if ($rows_count === count($logs)) {
                foreach ($logs as &$log) {
                    $log['id'] = $last_id++;
                    pocketlistsWebSoket::getInstance()->sendWebsocketData(
                        [
                            'client' => waRequest::server('HTTP_X_PL_API_CLIENT', ''),
                            'create_datetime' => pocketlistsHelper::convertDateToISO8601($log['create_datetime'])
                        ] + $log
                    );
                }
                pl2()->getEventDispatcher()->dispatch(
                    new pocketlistsEvent(
                        pocketlistsEventStorage::LOGS_INSERT,
                        $logs
                    )
                );
                return true;
            }
        }

        return false;
    }

    /**
     * @param pocketlistsLog $log
     */
    public function explain(pocketlistsLog $log)
    {

    }
}
