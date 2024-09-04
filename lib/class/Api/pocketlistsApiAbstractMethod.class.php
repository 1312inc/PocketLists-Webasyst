<?php

abstract class pocketlistsApiAbstractMethod extends waAPIMethod
{
    public const METHOD_GET    = 'GET';
    public const METHOD_POST   = 'POST';
    public const METHOD_PUT    = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PATCH  = 'PATCH';

    const MAX_LIMIT = 500;
    const DEFAULT_LIMIT = 30;

    private $request_body = null;

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
     * @param $data
     * @param array $fields
     * @param array $field_types
     * @return array
     */
    protected function filterFields($data, array $fields, array $field_types = [])
    {
        if (!empty($data) && is_array($data)) {
            return array_map(function ($el) use ($fields, $field_types) {
                $res = [];
                foreach (array_keys($el) as $key) {
                    if (in_array($key, $fields)) {
                        if (!isset($field_types[$key]) || $el[$key] === null) {
                            $res[$key] = $el[$key];
                            continue;
                        }
                        if ($field_types[$key] === 'int') {
                            $res[$key] = intval($el[$key]);
                        } elseif ($field_types[$key] === 'bool') {
                            $res[$key] = boolval($el[$key]);
                        } elseif ($field_types[$key] === 'float') {
                            $res[$key] = floatval($el[$key]);
                        } elseif ($field_types[$key] === 'double') {
                            $res[$key] = doubleval($el[$key]);
                        } elseif ($field_types[$key] === 'datetime') {
                            $res[$key] = $this->formatDatetimeToISO8601($el[$key]);
                        } else {
                            $res[$key] = $el[$key];
                        }
                    }
                }
                return $res;
            }, array_values($data));
        }

        return [];
    }

    /**
     * @param $sql_dt
     * @return string
     */
    protected function formatDatetimeToISO8601($sql_dt)
    {
        if (empty($sql_dt)) {
            return null;
        }
        try {
            $dt = new DateTime((string) $sql_dt);
            $dt->setTimezone(new DateTimeZone('UTC'));
            return $dt->format('Y-m-d\TH:i:s.u\Z');
        } catch (Exception $ex) {
            return $sql_dt;
        }
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
        $file_vo = new pocketlistsUploadedFileVO();
        $temp_path = $file_vo->getPath();
        waFiles::create($temp_path, true);

        /** @var pocketlistsFactory $attachment_factory */
        $attachment_factory = pl2()->getEntityFactory(pocketlistsAttachment::class);
        $path = wa()->getDataUrl("attachments/$item_id/", true, pocketlistsHelper::APP_ID, true);
        foreach ($files as &$_file) {
            $_file += [
                'file'      => '',
                'file_name' => '',
                'url'       => '',
                'uuid'      => null
            ];
            if (empty($_file['file']) || empty($_file['file_name'])) {
                continue;
            }
            $extension = pathinfo($_file['file_name'], PATHINFO_EXTENSION);
            if (in_array($extension, ['php', 'phtml', 'htaccess'])) {
                $_file['file'] = '';
                $_file['error'] = sprintf_wp('Files with extension .%s are not allowed to security considerations.', $extension);
                continue;
            }
            $item_file = base64_decode(ifset($_file, 'file', null));
            $_file['file'] = '';

            /** download to temp directory */
            $file_vo->setName(md5(uniqid(__METHOD__)).$_file['file_name']);
            $tmp_name = $temp_path.DIRECTORY_SEPARATOR.$file_vo->getName();
            if (!file_put_contents($file_vo->getFullPath(), $item_file)) {
                $_file['error'] = _w('File could not be saved.');
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
                ->setItemId($item_id)
                ->setFiletype($uploaded_file->getType())
                ->setUuid($_file['uuid']);
            $attachment_factory->insert($attachment);
            $_file = [
                'id'       => $attachment->getId(),
                'url'      => $path.$_file['file_name'],
                'filetype' => $attachment->getFiletype(),
            ] + $_file;
        }

        return $files;
    }

    protected function sorting(pocketlistsItemModel $item_model, $items = [])
    {
        $prev_by_id = [];
        $prev_by_uuid = [];
        $prev_item_ids = array_unique(array_filter(array_column($items, 'prev_item_id')));
        $prev_item_uuids = array_unique(array_filter(array_column($items, 'prev_item_uuid')));

        if ($prev_item_ids || $prev_item_uuids) {
            $where = [];
            $params = [];
            if ($prev_item_ids) {
                $where[] = 't.id IN (:ids)';
                $where[] = 'prev_id IN (:ids)';
                $params['ids'] = $prev_item_ids;
            }
            if ($prev_item_uuids) {
                $where[] = 't.uuid IN (:uuids)';
                $where[] = 'prev_uuid IN (:uuids)';
                $params['uuids'] = $prev_item_uuids;
            }

            $item_model->exec("SET @prev_item_id := 0, @prev_item_uuid := ''");
            $prev_items = $item_model->query(" 
                SELECT * FROM (
                    SELECT id, list_id, sort, `rank`, uuid, 
                        @prev_id AS prev_id,
                        @prev_uuid AS prev_uuid,
                        @prev_id := id AS _,
                        @prev_uuid := IF(uuid IS NOT NULL, uuid, NULL) AS __
                        FROM pocketlists_item
                        ORDER BY list_id, sort, `rank`
                ) AS t
                WHERE ".implode(' OR ', $where), $params
            )->fetchAll();

            foreach ($prev_items as $_prev_item) {
                $_prev_item = array_diff_key($_prev_item, array_fill_keys(['_', '__'], 0));
                if (in_array($_prev_item['id'], $prev_item_ids) || in_array($_prev_item['uuid'], $prev_item_uuids)) {
                    if (!empty($_prev_item['id'])) {
                        $prev_by_id[$_prev_item['id']] = $_prev_item;
                    } else {
                        $prev_by_uuid[$_prev_item['uuid']] = $_prev_item;
                    }
                } else {
                    if (ifset($prev_by_id, $_prev_item['prev_id'], [])) {
                        $prev_by_id[$_prev_item['prev_id']] += [
                            'next_sort' => $_prev_item['sort'],
                            'next_rank' => $_prev_item['rank']
                        ];
                    } elseif (ifset($prev_by_uuid, $_prev_item['prev_uuid'], [])) {
                        $prev_by_uuid[$_prev_item['prev_uuid']] += [
                            'next_sort' => $_prev_item['sort'],
                            'next_rank' => $_prev_item['rank']
                        ];
                    }
                }
            }
            unset($prev_items, $prev_item_ids, $prev_item_uuids);
        }

        /** сортировка по prev_item_uuid */
        $iter = count($items);
        $iter = $iter * $iter;
        do {
            $iter--;
            $counter = 1;
            $ext_item = array_pop($items);
            $ext_uuid = ifset($ext_item, 'uuid', null);
            $ext_prev_uuid = ifset($ext_item, 'prev_item_uuid', null);
            if (is_null($ext_uuid) && is_null($ext_prev_uuid)) {
                array_unshift($items, $ext_item);
                continue;
            }
            foreach ($items as $int_item) {
                $curr_uuid = ifset($int_item, 'uuid', null);
                $curr_prev_uuid = ifset($int_item, 'prev_item_uuid', null);
                if (isset($ext_uuid, $curr_prev_uuid) && $ext_uuid === $curr_prev_uuid) {
                    // вставляем НАД текущим
                    $counter--;
                    $items = array_merge(
                        array_slice($items, 0, $counter),
                        [$ext_item],
                        array_slice($items, $counter)
                    );
                    unset($ext_item);
                    break;
                } elseif (isset($ext_prev_uuid, $curr_uuid) && $ext_prev_uuid === $curr_uuid) {
                    // вставляем ПОД текущим
                    $items = array_merge(
                        array_slice($items, 0, $counter),
                        [$ext_item],
                        array_slice($items, $counter)
                    );
                    unset($ext_item);
                    break;
                }
                $counter++;
            }
            if (isset($ext_item)) {
                array_unshift($items, $ext_item);
            }
        } while ($iter > 1);

        $p_sort_rank = pocketlistsSortRank::getInstance();
        $sort_info = $item_model->query("
            SELECT list_id, MIN(sort) AS sort_min, MAX(sort) AS sort_max FROM pocketlists_item
            WHERE list_id IN (:list_ids)
            GROUP BY list_id
        ", ['list_ids' => array_column($items, 'list_id')])->fetchAll('list_id');
        foreach ($items as &$_item) {
            if (isset($_item['sort'])) {
                $_item['rank'] = ifset($_item, 'rank', '');
                continue;
            }
            if (!isset($_item['list_id'])) {
                $_item['sort'] = 0;
                $_item['rank'] = '';
                continue;
            }

            $srt = 0;
            $rnk = '';
            if (isset($_item['prev_item_id']) || isset($_item['prev_item_uuid'])) {
                if (isset($_item['prev_item_id'])) {
                    $extreme_item = ifempty($prev_by_id, $_item['prev_item_id'], []);
                } else {
                    $extreme_item = ifempty($prev_by_uuid, $_item['prev_item_uuid'], []);
                }
                $list_id = ifempty($extreme_item, 'list_id', null);
                if ($list_id == $_item['list_id']) {
                    $p_sort_rank->new(
                        (int) ifset($extreme_item, 'sort', 0),
                        ifempty($extreme_item, 'rank', '0')
                    );
                    list($srt, $rnk) = $p_sort_rank->between(
                        (int) ifset($extreme_item, 'next_sort', 0),
                        ifempty($extreme_item, 'next_rank', '0')
                    );
                } elseif (is_null($list_id)) {
                    /** добавляем в конец списка */
                    $p_sort_rank->new((int) ifset($sort_info, $_item['list_id'], 'sort_max', 0), '0');
                    list($srt) = $p_sort_rank->next();
                }
            } else {
                $sort_min = ifset($sort_info, $_item['list_id'], 'sort_min', null);
                if (!is_null($sort_min)) {
                    /** добавляем в начало списка */
                    $p_sort_rank->new((int) ifset($sort_info, $_item['list_id'], 'sort_min', 0), '0');
                    list($srt) = $p_sort_rank->previous();
                }
            }
            $_item['sort'] = $srt;
            $_item['rank'] = $rnk;
        }

        return $items;
    }
}
