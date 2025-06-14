<?php

abstract class pocketlistsApiAbstractMethod extends waAPIMethod
{
    use pocketlistsDataHelperTrait;

    public const METHOD_GET    = 'GET';
    public const METHOD_POST   = 'POST';
    public const METHOD_PUT    = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PATCH  = 'PATCH';
    public const ACTIONS  = ['patch', 'update'];

    const MAX_LIMIT = 500;
    const DEFAULT_LIMIT = 30;

    private $request_body = null;

    public function __construct()
    {
        parent::__construct();
        $this->response = [
            'status_code' => 'ok',
            'error'       => '',
            'data'        => []
        ];
    }

    protected function setError($error = '')
    {
        $this->response['status_code'] = 'error';
        $this->response['error'] = $error;
    }

    /**
     * @param $internal
     * @param $param
     * @return array
     * @throws pocketlistsApiException
     * @throws waException
     */
    public function getResponse($internal = false)
    {
        if (!$internal) {
            // check request method
            $request_method = strtoupper(waRequest::method());
            if ((is_array($this->method) && !in_array($request_method, $this->method)) ||
                (!is_array($this->method) && $request_method != $this->method)
            ) {
                throw new pocketlistsApiException(sprintf(_ws('Method %s not allowed'), $request_method), 405);
            }
        }

        $this->execute();

        return $this->response;
    }

    /**
     * @param $name
     * @param $required
     * @return array|int|mixed|null
     * @throws pocketlistsAPIException
     * @throws waException
     */
    public function get($name, $required = false)
    {
        $v = waRequest::get($name);
        if ($required && !$v) {
            throw new pocketlistsApiException(sprintf(_ws('Required parameter is missing: “%s”.'), $name), 400);
        }

        return $v;
    }

    /**
     * @param $name
     * @param $required
     * @return array|int|mixed|null
     * @throws pocketlistsAPIException
     * @throws waException
     */
    public function post($name, $required = false)
    {
        $v = waRequest::post($name);
        if ($required && !$v) {
            throw new pocketlistsApiException(sprintf(_ws('Required parameter is missing: “%s”.'), $name), 400);
        }

        return $v;
    }

    /**
     * @return mixed|null
     */
    protected function readBodyAsJson()
    {
        if ($this->request_body === null) {
            $this->request_body = '';
            $contents = file_get_contents('php://input');
            if (is_string($contents) && strlen($contents)) {
                $this->request_body = $contents;
            }
        }

        if ($this->request_body) {
            return json_decode($this->request_body, true);
        }

        return null;
    }

    /**
     * @return pocketlistsUser
     */
    protected function getUser()
    {
        return pl2()->getUser();
    }

    /**
     * @param $api_client_id
     * @param $user_id
     * @return string
     * @throws waException
     */
    protected function getApiToken($api_client_id, $user_id = null)
    {
        static $at_model;
        if (empty($at_model)) {
            $at_model = new waApiTokensModel();
        }
        if (empty($user_id)) {
            $user_id = $this->getUser()->getId();
        }

        return $at_model->getToken($api_client_id, $user_id, pocketlistsConfig::API_TOKEN_SCOPE);
    }

    /**
     * Add record to table wa_log
     *
     * @param string $action
     * @param mixed $params
     * @param int $subject_contact_id
     * @param int $contact_id - actor contact id
     * @throws waException
     * @return bool|int
     */
    public function systemLogAction($action, $params = null, $subject_contact_id = null, $contact_id = null)
    {
        static $log_model;
        if (empty($log_model)) {
            if (!class_exists('waLogModel')) {
                wa('webasyst');
            }
            $log_model = new waLogModel();
        }

        try {
            return $log_model->add($action, $params, $subject_contact_id, $contact_id);
        } catch (Exception $ex) {
            pocketlistsLogger::error(
                sprintf(
                    'Error on systemLogAction. Error: %s. Trace: %s',
                    $ex->getMessage(),
                    $ex->getTraceAsString()
                ),
                'system_log_action.log'
            );
            return false;
        }
    }

    /**
     * @param $datetime
     * @return string|null
     */
    protected function convertDatetimeToServer($datetime)
    {
        if (empty($datetime)) {
            return null;
        }
        try {
            $dt = date_create($datetime, new DateTimeZone('UTC'));
            if ($dt instanceof DateTime) {
                $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
                return $dt->format('Y-m-d H:i:s');
            }
        } catch (Exception $ex) {
        }
        return null;
    }

    /**
     * @param int $item_id
     * @param array $files [
     *      'file' => --base64--,
     *      'file_name' => 'abc.jpg'
     * ]
     * @return array
     * @throws waException
     */
    protected function updateFiles($item_id, $files = [])
    {
        $now = date('Y-m-d H:i:s');
        $file_vo = new pocketlistsUploadedFileVO();
        $temp_path = $file_vo->getPath();
        waFiles::create($temp_path, true);

        /** @var pocketlistsFactory $attachment_factory */
        $attachment_factory = pl2()->getEntityFactory(pocketlistsAttachment::class);
        foreach ($files as &$_file) {
            $_file += [
                'item_id'         => $item_id,
                'file'            => '',
                'file_name'       => '',
                'ext'             => '',
                'size'            => null,
                'storage'         => 'protected',
                'upload_datetime' => $now,
                'download_url'    => '',
                'preview_url'     => '',
                'uuid'            => null,
                'errors'          => []
            ];
            if (empty($_file['file']) || empty($_file['file_name'])) {
                continue;
            }
            $_file['file_name'] = waLocale::transliterate($_file['file_name']);
            $_file['file_name'] = preg_replace('/\s+/m', '_', $_file['file_name']);
            $extension = pocketlistsAttachment::getExtension($_file['file_name']);
            if (in_array($extension, ['php', 'phtml', 'htaccess'])) {
                unset($_file['file']);
                $_file['errors'][] = sprintf_wp('Files with extension .%s are not allowed to security considerations.', $extension);
                continue;
            }
            $item_file = base64_decode(ifset($_file, 'file', null));
            unset($_file['file']);

            /** download to temp directory */
            $file_vo->setName(md5(uniqid(__METHOD__)).$_file['file_name']);
            $tmp_name = $temp_path.DIRECTORY_SEPARATOR.$file_vo->getName();
            if (!file_put_contents($file_vo->getFullPath(), $item_file)) {
                $_file['errors'][] = _w('File could not be saved.');
                continue;
            }

            $uploaded_file = pocketlistsUploadedFileVO::createTempFromName($file_vo->getName());
            $uploaded_file->setItemId($item_id)->setName($_file['file_name']);

            if (!preg_match('//u', $_file['file_name'])) {
                $tmp_name = @iconv('windows-1251', 'utf-8//ignore', $_file['file_name']);
                if ($tmp_name) {
                    $_file['file_name'] = $tmp_name;
                }
            }
            if (file_exists($uploaded_file->getFullPath())) {
                $i = strrpos($_file['file_name'], '.');
                $_file['file_name'] = substr($_file['file_name'], 0, $i);
                $i = 1;
                while (file_exists(sprintf('%s%s-%s.%s', $uploaded_file->getPath().DIRECTORY_SEPARATOR, $_file['file_name'], $i, $extension))) {
                    $i++;
                }
                $_file['file_name'] = sprintf('%s-%s.%s', $_file['file_name'], $i, $extension);
                $uploaded_file->setName($_file['file_name']);
            }

            waFiles::move($tmp_name, $uploaded_file->getFullPath());

            /** @var pocketlistsAttachment $attachment */
            $attachment = $attachment_factory->createNew();
            $attachment->setFilename($_file['file_name'])
                ->setSize(filesize($uploaded_file->getFullPath()))
                ->setItemId($item_id)
                ->setUploadDatetime($_file['upload_datetime'])
                ->setUuid($_file['uuid']);
            $attachment_factory->insert($attachment);
            $_file = [
                'id'       => $attachment->getId(),
                'ext'      => $attachment->getExt(),
                'filename' => $attachment->getFilename(),
                'size'     => $attachment->getSize()
            ] + $_file;
            $_file = pocketlistsAttachment::setUrl($_file);
        }

        return $files;
    }

    protected function sorting($entity_type, $entities = [])
    {
        $prev_by_id = [];
        $prev_by_uuid = [];
        $key_id = 'prev_'.$entity_type.'_id';
        $key_uuid = 'prev_'.$entity_type.'_uuid';
        $prev_entity_ids = array_unique(array_filter(array_column($entities, $key_id)));
        $prev_entity_uuids = array_unique(array_filter(array_column($entities, $key_uuid)));

        switch ($entity_type) {
            case 'pocket':
                $parent_key = 'pl_id';
                break;
            case 'list':
                $parent_key = 'pocket_id';
                break;
            case 'item':
            default:
                $parent_key = 'list_id';
        }

        /** сортировка по prev_entity_ id/uuid */
        $iter = count($entities);
        $iter = $iter * $iter;
        do {
            $iter--;
            $counter = 1;
            $ext_entity = array_pop($entities);
            $ext_id = ifset($ext_entity, 'id', null);
            $ext_prev_id = ifset($ext_entity, $key_id, null);
            $ext_uuid = ifset($ext_entity, 'uuid', null);
            $ext_prev_uuid = ifset($ext_entity, $key_uuid, null);
            if (!isset($ext_id, $ext_prev_id) && !isset($ext_uuid, $ext_prev_uuid)) {
                array_unshift($entities, $ext_entity);
                continue;
            }
            foreach ($entities as $int_entity) {
                $curr_id = ifset($int_entity, 'id', null);
                $curr_prev_id = ifset($int_entity, $key_id, null);
                $curr_uuid = ifset($int_entity, 'uuid', null);
                $curr_prev_uuid = ifset($int_entity, $key_uuid, null);
                if (
                    (isset($ext_id, $curr_prev_id) && $ext_id === $curr_prev_id)
                    || (isset($ext_uuid, $curr_prev_uuid) && $ext_uuid === $curr_prev_uuid)
                ) {
                    // вставляем НАД текущим
                    $counter--;
                    $entities = array_merge(
                        array_slice($entities, 0, $counter),
                        [$ext_entity],
                        array_slice($entities, $counter)
                    );
                    unset($ext_entity);
                    break;
                } elseif (
                    (isset($ext_prev_id, $curr_id) && $ext_prev_id === $curr_id)
                    || (isset($ext_prev_uuid, $curr_uuid) && $ext_prev_uuid === $curr_uuid)
                ) {
                    // вставляем ПОД текущим
                    $entities = array_merge(
                        array_slice($entities, 0, $counter),
                        [$ext_entity],
                        array_slice($entities, $counter)
                    );
                    unset($ext_entity);
                    break;
                }
                $counter++;
            }
            if (isset($ext_entity)) {
                array_unshift($entities, $ext_entity);
            }
        } while ($iter > 0);

        $model = pl2()->getModel();
        if ($prev_entity_ids || $prev_entity_uuids) {
            $where = [];
            $params = [];
            if ($prev_entity_ids) {
                $where[] = 't.id IN (:ids)';
                $where[] = 't.prev_id IN (:ids)';
                $params['ids'] = $prev_entity_ids;
            }
            if ($prev_entity_uuids) {
                $where[] = 't.uuid IN (:uuids)';
                $where[] = 't.prev_uuid IN (:uuids)';
                $params['uuids'] = $prev_entity_uuids;
            }

            $model->exec("SET @prev_id := 0, @grp_id := 0, @prev_uuid := ''");
            switch ($entity_type) {
                case 'pocket':
                    $sub_sql = "
                        SELECT
                            id, sort, `rank`, uuid,
                            1312 as pl_id,
                            @prev_id AS prev_id,
                            @prev_uuid AS prev_uuid,
                            @prev_id := pp.id AS _,
                            @prev_uuid := pp.uuid AS __
                        FROM pocketlists_pocket pp
                        ORDER BY pp.sort, pp.`rank`
                    ";
                    break;
                case 'list':
                    $sub_sql = "SELECT 
                        pl.id, pi.name, pl.pocket_id, pl.sort, pl.`rank`, pi.uuid,
                        IF(@grp_id = pl.pocket_id, pl.pocket_id, NULL) AS grp_id,
                        IF((@grp_id = pl.pocket_id OR @grp_id IS NULL), @prev_id, NULL) AS prev_id,
                        IF(@grp_id = pl.pocket_id, @prev_uuid, '') AS prev_uuid, 
                        @grp_id := pl.pocket_id AS _,
                        @prev_id := pl.id AS __,
                        @prev_uuid := pi.uuid AS ___
                    FROM pocketlists_list pl
                    LEFT JOIN pocketlists_item pi ON pl.key_item_id = pi.id 
                    ORDER BY pl.pocket_id, pl.sort, pl.`rank`";
                    break;
                case 'item':
                default:
                    $sub_sql = "SELECT 
                        id, name, list_id, sort, `rank`, uuid,
                        IF(@grp_id = list_id, list_id, 0) AS lst_id,
                        IF(@grp_id = list_id, @prev_id, 0) AS prev_id,
                        IF(@grp_id = list_id, @prev_uuid, '') AS prev_uuid, 
                        @grp_id := list_id AS _,
                        @prev_id := id AS __,
                        @prev_uuid := uuid AS ___
                    FROM pocketlists_item t1
                    ORDER BY t1.list_id, t1.sort, t1.`rank`";
            }
            $prev_entities = $model->query(" 
                SELECT * FROM ($sub_sql) AS t
                WHERE ".implode(' OR ', $where), $params
            )->fetchAll();

            $arr_1 = array_fill_keys(['_', '__', '___'], 0);
            $arr_2 = array_fill_keys(['next_sort', 'next_rank'], null);
            foreach ($prev_entities as $_prev_entity) {
                $_prev_entity = array_diff_key($_prev_entity, $arr_1) + $arr_2;
                if ($_prev_entity['prev_id'] === '0') {
                    $_prev_entity['prev_id'] = null;
                }
                if (in_array($_prev_entity['id'], $prev_entity_ids)) {
                    $prev_by_id[$_prev_entity['id']] = $_prev_entity;
                } elseif (in_array($_prev_entity['uuid'], $prev_entity_uuids)) {
                    $prev_by_uuid[$_prev_entity['uuid']] = $_prev_entity;
                } else {
                    if (
                        ifset($prev_by_id, $_prev_entity['prev_id'], [])
                        && $prev_by_id[$_prev_entity['prev_id']][$parent_key] == $_prev_entity[$parent_key]
                    ) {
                        $prev_by_id[$_prev_entity['prev_id']]['next_sort'] = $_prev_entity['sort'];
                        $prev_by_id[$_prev_entity['prev_id']]['next_rank'] = $_prev_entity['rank'];
                    } elseif (ifset($prev_by_uuid, $_prev_entity['prev_uuid'], [])) {
                        $prev_by_uuid[$_prev_entity['prev_uuid']]['next_sort'] = $_prev_entity['sort'];
                        $prev_by_uuid[$_prev_entity['prev_uuid']]['next_rank'] = $_prev_entity['rank'];
                    }
                }
            }
            unset($prev_entities, $_prev_entity, $prev_entity_ids, $prev_entity_uuids, $params, $where, $arr_1, $arr_2);
        }

        $p_sort_rank = pocketlistsSortRank::getInstance();
        switch ($entity_type) {
            case 'pocket':
                $sort_info = $model->query("
                    SELECT MIN(sort) AS sort_min, MAX(sort) AS sort_max FROM pocketlists_pocket
                ")->fetchAssoc();
                break;
            case 'list':
                $sort_info = $model->query("
                    SELECT pocket_id AS grp_id, MIN(sort) AS sort_min, MAX(sort) AS sort_max FROM pocketlists_list
                    WHERE pocket_id IN (:pocket_ids) OR pocket_id IS NULL
                    GROUP BY pocket_id
                ", ['pocket_ids' => array_unique(array_column($entities, $parent_key))])->fetchAll('grp_id');
                break;
            case 'item':
            default:
                $sort_info = $model->query("
                    SELECT list_id AS grp_id, MIN(sort) AS sort_min, MAX(sort) AS sort_max FROM pocketlists_item
                    WHERE list_id IN (:list_ids)
                    GROUP BY list_id
                ", ['list_ids' => array_unique(array_column($entities, $parent_key))])->fetchAll('grp_id');
        }
        foreach ($entities as &$_entity) {
            if (isset($_entity['sort'])) {
                $_entity['rank'] = ifset($_entity, 'rank', '');
                continue;
            }

            $srt = 0;
            $rnk = '';
            if (isset($_entity[$key_id]) || isset($_entity[$key_uuid])) {
                if (isset($_entity[$key_id])) {
                    $extreme_entity = ifempty($prev_by_id, $_entity[$key_id], []);
                } else {
                    $extreme_entity = ifempty($prev_by_uuid, $_entity[$key_uuid], []);
                }
                $group_id = ifempty($extreme_entity, $parent_key, null);
                if ($group_id == $_entity[$parent_key]) {
                    $p_sort_rank->new(
                        (int) ifset($extreme_entity, 'sort', 0),
                        ifempty($extreme_entity, 'rank', '0')
                    );
                    if (!isset($extreme_entity['next_sort'])) {
                        list($srt) = $p_sort_rank->next();
                    } else {
                        list($srt, $rnk) = $p_sort_rank->between(
                            (int) ifset($extreme_entity, 'next_sort', 0),
                            ifempty($extreme_entity, 'next_rank', '0')
                        );
                    }

                    /** обновляем sort/rank если в сортировке участвует сущность на которую ссылается через prev_ */
                    if (!empty($_entity['id'])) {
                        if (!empty($prev_by_id[$_entity['id']])) {
                            $prev_by_id[$_entity['id']]['sort'] = $srt;
                            $prev_by_id[$_entity['id']]['rank'] = $rnk;
                            $prev_by_id[$_entity['id']]['next_sort'] = $extreme_entity['next_sort'];
                            $prev_by_id[$_entity['id']]['next_rank'] = $extreme_entity['next_rank'];
                        } elseif (!empty($prev_by_uuid[$_entity['id']])) {
                            $prev_by_uuid[$_entity['id']]['sort'] = $srt;
                            $prev_by_uuid[$_entity['id']]['rank'] = $rnk;
                            $prev_by_uuid[$_entity['id']]['next_sort'] = $extreme_entity['next_sort'];
                            $prev_by_uuid[$_entity['id']]['next_rank'] = $extreme_entity['next_rank'];
                        }
                    } elseif (!empty($_entity['uuid'])) {
                        $prev_by_uuid[$_entity['uuid']]['list_id'] = $_entity['list_id'];
                        $prev_by_uuid[$_entity['uuid']]['sort'] = $srt;
                        $prev_by_uuid[$_entity['uuid']]['rank'] = $rnk;
                        $prev_by_uuid[$_entity['uuid']]['next_sort'] = $extreme_entity['next_sort'];
                        $prev_by_uuid[$_entity['uuid']]['next_rank'] = $extreme_entity['next_rank'];
                    }
                } elseif (is_null($group_id)) {
                    /** добавляем в конец списка */
                    $p_sort_rank->new((int) ifset($sort_info, $_entity[$parent_key], 'sort_max', 0), '0');
                    list($srt) = $p_sort_rank->next();
                    $sort_info[$_entity[$parent_key]]['sort_max'] = $srt;
                }
            } else {
                $sort_min = ifset($sort_info, $_entity[$parent_key], 'sort_min', null);
                if (!is_null($sort_min)) {
                    /** добавляем в начало списка */
                    $p_sort_rank->new((int) $sort_min, '0');
                    list($srt) = $p_sort_rank->previous();
                    $sort_info[$_entity[$parent_key]]['sort_min'] = $srt;
                }
                if (empty($_entity['id']) && empty($prev_by_uuid[$_entity['uuid']]) && !empty($_entity['list_id'])) {
                    $prev_by_uuid[$_entity['uuid']]['sort'] = $srt;
                    $prev_by_uuid[$_entity['uuid']]['rank'] = $rnk;
                    $prev_by_uuid[$_entity['uuid']]['list_id'] = $_entity['list_id'];
                    $prev_by_uuid[$_entity['uuid']]['next_sort'] = $srt + 1;
                    $prev_by_uuid[$_entity['uuid']]['next_rank'] = '';
                }
            }
            $_entity['sort'] = $srt;
            $_entity['rank'] = $rnk;
        }
        unset($_entity);

        return $entities;
    }

    /**
     * @param string $entity
     * @param array $uuids
     * @return array
     * @throws waDbException
     * @throws waException
     */
    protected function getEntitiesByUuid($entity, $uuids = [])
    {
        if (
            !in_array($entity, ['pocket', 'list', 'item', 'comment', 'attachment', 'location'])
            || empty($uuids)
        ) {
            return [];
        } elseif (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $model = pl2()->getModel();
        $table = 'pocketlists_'.$entity;

        return $model->query(
            "SELECT * FROM $table ent"
            .($entity === 'list' ? ' JOIN pocketlists_item pi ON pi.id = ent.key_item_id ' : ' ')
            ."WHERE uuid IN (s:uuids)",
            ['uuids' => array_unique($uuids)]
        )->fetchAll('uuid');
    }

    /**
     * @param array $data
     * @param array$fields
     * @param array $field_types
     * @return array
     */
    protected function responseWrapper($data = [], $fields = [], array $field_types = [])
    {
        if (!is_array($data)) {
            return  [];
        }
        foreach ($data as &$_data) {
            $_data = [
                'success' => !empty($_data['success']),
                'errors'  => ifset($_data, 'errors', []),
                'data'    => $this->singleFilterFields(ifset($_data, []), $fields, $field_types)
            ];
        }

        return array_values($data);
    }

    protected function responseListWrapper($data = [], $fields = [], array $field_types = [])
    {
        if (!is_array($data)) {
            return  [];
        }
        foreach ($data as &$_data) {
            $_data = $this->singleFilterFields(ifset($_data, []), $fields, $field_types);
        }

        return array_values($data);
    }

    protected function setAnnouncements($items_ok = [])
    {
        $items_due = [];
        $url = rtrim(wa()->getConfig()->getHostUrl(), '/').wa()->getConfig()->getBackendUrl(true);
        $html_text = "%s <a href=\"/{$url}pocketlists/todos/upnext\"> &rarr;</a>";
        foreach ($items_ok as $_item) {
            if (($_item['due_date'] != null || $_item['due_datetime'] != null) && $_item['status'] === pocketlistsItem::STATUS_UNDONE) {
                if (isset($_item['assigned_contact_id'])) {
                    $_item['contact_id'] = $_item['assigned_contact_id'];
                }
                if (mb_strlen($_item['name']) > 128) {
                    $_item['name'] = mb_substr($_item['name'], 0, 125).'...';
                }
                $items_due[] = $_item + [
                    'text'      => sprintf($html_text, $_item['name']),
                    'datetime'  => $_item['due_datetime'] ?? $_item['due_date'],
                    'is_pinned' => $_item['priority'] > 0 ? 1 : 0
                ];
            }
        }

        if ($items_due) {
            pocketlistsAnnouncement::addAnnouncements($items_due);
        }
    }

    protected function updateAnnouncements($items_ok = [])
    {
        $items_del = [];
        $items_upd = [];
        foreach ($items_ok as $_item) {
            if (
                $_item['status'] === pocketlistsItem::STATUS_DONE
                || !isset($_item['due_date'], $_item['due_datetime'])
            ) {
                $items_del[] = $_item;
            }
            if ($_item['status'] === pocketlistsItem::STATUS_DONE) {
                continue;
            }
            if (
                !empty($_item['due_date'])
                || !empty($_item['due_datetime'])
            ) {
                $items_del[] = $_item;
                $items_upd[] = $_item;
            }
        }

        if ($items_del) {
            $this->deleteAnnouncements($items_del);
            pl2()->getModel('pocketlistsItemLink')->deleteByField([
                'item_id'     => array_column($items_del, 'id'),
                'app'         => pocketlistsAnnouncement::APP,
                'entity_type' => pocketlistsAnnouncement::ENTITY
            ]);
        }
        if ($items_upd) {
            $this->setAnnouncements($items_upd);
        }
    }

    protected function deleteAnnouncements($items_ok = [])
    {
        $ids = array_column($items_ok, 'id');
        pocketlistsAnnouncement::removeAnnouncements($ids);
    }

    /**
     * @param $tags
     * @return array
     */
    protected function tagFilter($tags = [])
    {
        if (empty($tags) || !is_array($tags)) {
            return [];
        }

        $tags = array_map(function ($_tag) {
            return trim((string) $_tag);
        }, $tags);

        return array_filter($tags, function ($_tag) {
            return $_tag !== '';
        });
    }

    /**
     * @param $item
     * @return int
     */
    protected function getCalcPriority($item = [])
    {
        $now_priority = ifset($item, 'priority', 0);
        $due_date = ifset($item, 'due_date', null);
        $due_datetime = ifset($item, 'due_datetime', null);
        $calc_priority = pocketlistsHelper::calcPriorityOnDueDate($due_date, $due_datetime);

        return (int) max($now_priority, $calc_priority);
    }

    /**
     * @param $teammates_ids
     * @param $offset
     * @param $limit
     * @param $switch
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public static function getTeammates($teammates_ids = [], $offset = 0, $limit = self::DEFAULT_LIMIT, $switch = 0)
    {
        $result = [];
        $root_url = rtrim(wa()->getConfig()->getHostUrl(), '/');
        $teammates_ids = (empty($teammates_ids) ? pocketlistsRBAC::getAccessContacts() : array_intersect($teammates_ids, pocketlistsRBAC::getAccessContacts()));

        /** @var pocketlistsContactFactory $contact_factory */
        $contact_factory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contact_factory->getTeammates($teammates_ids, true, false, true);
        $count = count($teammates);
        $teammates = array_slice($teammates, $offset, $limit);

        $assigned_list_counts = pl2()->getModel('pocketlistsList')->query('
            SELECT pi2.assigned_contact_id, count(pl.id) list_count FROM pocketlists_list pl 
            JOIN pocketlists_item pi2 ON pi2.key_list_id = pl.id
            WHERE pl.archived = 0 AND pi2.assigned_contact_id IN (i:user_ids)
            GROUP BY pi2.assigned_contact_id
        ', ['user_ids' => $teammates_ids])->fetchAll('assigned_contact_id');

        /** @var pocketlistsContact $_teammate */
        foreach ($teammates as $_teammate) {
            /** @var pocketlistsItemsCount $items_info */
            $items_info = $_teammate->getItemsInfo();
            $result[] = [
                'id'            => $_teammate->getId(),
                'name'          => $_teammate->getName(),
                'username'      => $_teammate->getUsername(),
                'photo_url'     => $root_url.$_teammate->getPhotoUrl(),
                'user_pic'      => $root_url.$_teammate->getUserPic(),
                'status'        => $_teammate->getStatus(),
                'team_role'     => $_teammate->getTeamrole(),
                'login'         => $_teammate->getLogin(),
                'me'            => $_teammate->isMe(),
                'exists'        => $_teammate->isExists(),
                'last_activity' => $_teammate->getLastActivity(),
                'email'         => $_teammate->getEmail(),
                'locale'        => $_teammate->getLocale(),
                'extended_data' => [
                    'lists_count'              => (int) ifset($assigned_list_counts, $_teammate->getId(), 'list_count', 0),
                    'items_count'              => $items_info->getCount(!!$switch),
                    'items_priority_count'     => $items_info->getCountPriority(!!$switch),
                    'max_priority'             => $items_info->getMaxPriority(!!$switch),
                    'items_max_priority_count' => $items_info->getCountMaxPriority(!!$switch),
                    'items_priorities_count'   => $items_info->getCountPriorities(!!$switch)
                ]
            ];
        }

        return [$result, $count];
    }

    protected function getLinks($item_ids)
    {
        $result = [];
        $part_sql = '';
        $order_format = '';
        $select = "SELECT pil.*, '' AS entity_title, '' AS entity_link";
        $base_url = wa()->getUrl(true).wa()->getConfig()->getBackendUrl();
        $apps_exclude = [pocketlistsAnnouncement::APP];
        try {
            if (wa()->appExists('shop')) {
                $order_format = wa('shop')->getConfig()->getOrderFormat();
                $part_sql = "LEFT JOIN shop_order sor ON pil.app = 'shop' AND sor.id = pil.entity_id";
            } else {
                $apps_exclude[] = pocketlistsAppLinkShop::APP;
            }
            if (wa()->appExists('tasks')) {
                $select .= ',tt.name, tt.project_id, tt.`number`';
                $part_sql .= " LEFT JOIN tasks_task tt ON pil.app = 'tasks' AND tt.id = pil.entity_id";
            } else {
                $apps_exclude[] = pocketlistsAppLinkTasks::APP;
            }
        } catch (Exception $e) {}
        if (empty($part_sql)) {
            return $result;
        }

        $links_in_db = pl2()->getModel(pocketlistsItemLink::class)->query(
            "$select FROM pocketlists_item_link pil $part_sql WHERE pil.item_id IN (i:ids) AND pil.app NOT IN (s:apps) AND pil.entity_type NOT IN (s:entity)", [
                'ids'    => $item_ids,
                'apps'   => $apps_exclude,
                'entity' => [pocketlistsAnnouncement::ENTITY]
            ]
        )->fetchAll();
        foreach ($links_in_db as $_link) {
            if (!isset($result[$_link['item_id']])) {
                $result[$_link['item_id']] = [];
            }
            switch ($_link['app']) {
                case 'shop':
                    $_link['entity_title'] = str_replace('{$order.id}', $_link['entity_id'], $order_format);
                    $_link['entity_link'] = sprintf("$base_url/shop/?action=orders#/order/%d/", $_link['entity_id']);
                    break;
                case 'tasks':
                    $project_id = ifset($_link,'project_id', '');
                    $number = ifset($_link,'number', '');
                    $_link['entity_title'] = "$project_id.$number ".ifset($_link, 'name', '');
                    $_link['entity_link'] = sprintf("$base_url/tasks/#/task/%d.%d/", $project_id, $number);
                    break;
            }
            $result[$_link['item_id']][] = $this->singleFilterFields(
                $_link,
                ['id', 'item_id', 'app', 'entity_type', 'entity_id', 'entity_title', 'entity_link', 'data'],
                ['id' => 'int', 'item_id' => 'int']
            );
        }

        return $result;
    }
}
