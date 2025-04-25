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
        pocketlistsLog::ACTION_UNCOMPLETE,
        pocketlistsLog::ACTION_SHARE,
        pocketlistsLog::ACTION_UNSHARE
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
     * @param $data
     * @return bool
     * @throws waException
     */
    public static function multipleAdd($data = [])
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        $default = [
            'action'              => null,
            'entity_type'         => null,
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

        $logs = [];
        while ($_log = array_shift($data)) {
            $id = ifset($_log, 'id', null);
            $action = ifempty($_log, 'action', null);
            $entity_type = ifempty($_log, 'entity_type', null);
            if (!in_array($action, self::ACTIONS) || !in_array($entity_type,  self::ENTITIES)) {
                continue;
            }

            $params = $_log;
            unset($_log['create_datetime'], $params['action'], $params['id']);
            $_log = array_intersect_key($_log, $default) + $default;
            $_log['params_for_socket'] = json_encode([$entity_type => $params], JSON_UNESCAPED_UNICODE);
            if (in_array($action, [pocketlistsLog::ACTION_DELETE, pocketlistsLog::ACTION_UNSHARE])) {
                switch ($entity_type) {
                    case pocketlistsLog::ENTITY_ITEM:
                        unset($params['name'], $params['note']);
                        break;
                    case pocketlistsLog::ENTITY_COMMENT:
                        unset($params['comment']);
                        break;
                }
            }
            $_log['params'] = json_encode([$entity_type => $params], JSON_UNESCAPED_UNICODE);

            if ($id) {
                $_log[$entity_type.'_id'] = $id;
            }
            $logs[] = $_log;
        }

        $log_model = pl2()->getModel(pocketlistsLog::class);
        $result = $log_model->multipleInsert($logs);
        if ($result instanceof waDbResult && $result->getResult()) {
            $last_id = $result->lastInsertId();
            $rows_count = $result->affectedRows();
            if ($rows_count === count($logs)) {
                foreach ($logs as &$log) {
                    $log['id'] = $last_id++;
                    $log['params'] = $log['params_for_socket'];
                    unset($log['params_for_socket']);
                }
                self::websocketMegaphone($logs);
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
     * @param $logs
     * @return void|null
     * @throws waException
     */
    private static function websocketMegaphone($logs = [])
    {
        if (empty($logs)) {
            return null;
        }
        $entity_type = reset($logs);
        $entity_type = ifempty($entity_type, 'entity_type', null);

        switch ($entity_type) {
            case pocketlistsLog::ENTITY_POCKET:
                $pocket_ids = array_filter(array_unique(array_column($logs, 'pocket_id')));
                $users_access_ids = pocketlistsRBAC::getAccessContactsByPockets($pocket_ids);
                break;
            case pocketlistsLog::ENTITY_LIST:
            case pocketlistsLog::ENTITY_ITEM:
                $list_ids = array_filter(array_unique(array_column($logs, 'list_id')));
                $users_access_ids = pocketlistsRBAC::getAccessContactsByLists($list_ids);
                break;
            default:
                return null;
//            case pocketlistsLog::ENTITY_COMMENT:
//            case pocketlistsLog::ENTITY_LOCATION:
//            case pocketlistsLog::ENTITY_ATTACHMENT:
        }

        $ws = pocketlistsWebSoket::getInstance();
        foreach ($logs as $log) {
            $users = null;
            switch ($entity_type) {
                case pocketlistsLog::ENTITY_POCKET:
                    if ($log['pocket_id'] && $users_access_ids[$log['pocket_id']]) {
                        $users = $users_access_ids[$log['pocket_id']];
                    }
                    break;
                case pocketlistsLog::ENTITY_ITEM:
                    if ($log['list_id'] && $users_access_ids[$log['list_id']]) {
                        $users = $users_access_ids[$log['list_id']];
                    } elseif (is_null($log['list_id']) && $log['assigned_contact_id']) {
                        $users = [$log['assigned_contact_id']];
                    }
                    break;
                default:
                    continue 2;
            }

            if ($users) {
                foreach ($users as $_user_id) {
                    $channel = $ws->getChannel($_user_id);
                    $ws->sendWebsocketData(
                        [
                            'client' => waRequest::server('HTTP_X_PL_API_CLIENT', ''),
                            'create_datetime' => pocketlistsHelper::convertDateToISO8601($log['create_datetime'])
                        ] + $log,
                        $channel
                    );
                }
            }
        }
    }

    /**
     * @param pocketlistsLog $log
     */
    public function explain(pocketlistsLog $log)
    {

    }
}
