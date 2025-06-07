<?php

trait pocketlistsDataHelperTrait
{
    /**
     * @param $sql_dt
     * @param $tz
     * @return string|null
     */
    protected function formatDatetimeToISO8601($sql_dt, $tz = 'UTC')
    {
        return pocketlistsHelper::convertDateToISO8601($sql_dt, $tz);
    }

    /**
     * @param array $data
     * @param array $fields
     * @param array $field_types
     * @return array
     */
    protected function singleFilterFields($data, array $fields, array $field_types = [])
    {
        $res = [];
        foreach (array_keys($data) as $key) {
            if (in_array($key, $fields)) {
                if (!isset($field_types[$key]) || $data[$key] === null) {
                    $res[$key] = $data[$key];
                    continue;
                }
                if ($field_types[$key] === 'int') {
                    $res[$key] = intval($data[$key]);
                } elseif ($field_types[$key] === 'bool') {
                    $res[$key] = boolval($data[$key]);
                } elseif ($field_types[$key] === 'float') {
                    $res[$key] = floatval($data[$key]);
                } elseif ($field_types[$key] === 'double') {
                    $res[$key] = doubleval($data[$key]);
                } elseif ($field_types[$key] === 'datetime') {
                    $res[$key] = $this->formatDatetimeToISO8601($data[$key]);
                } elseif ($field_types[$key] === 'dateiso') {
                    $res[$key] = $this->formatDatetimeToISO8601($data[$key], null);
                } else {
                    $res[$key] = $data[$key];
                }
            }
        }

        return $res;
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
                return $this->singleFilterFields($el, $fields, $field_types);
            }, array_values($data));
        }

        return [];
    }

    /**
     * @param $entity
     * @param $action
     * @param array $logs
     * @return void
     * @throws waException
     */
    public function saveLog($entity, $action, $logs = [])
    {
        if ($logs) {
            $logs = $this->filterFields(
                $logs,
                [
                    'id',
                    'list_id',
                    'contact_id',
                    'parent_id',
                    'sort',
                    'rank',
                    'has_children',
                    'status',
                    'priority',
                    'calc_priority',
                    'create_datetime',
                    'update_datetime',
                    'complete_datetime',
                    'client_touch_datetime',
                    'complete_contact_id',
                    'name',
                    'note',
                    'due_date',
                    'due_datetime',
                    'location_id',
                    'amount',
                    'currency_iso3',
                    'assigned_contact_id',
                    'favorite',
                    'repeat_frequency',
                    'repeat_interval',
                    'repeat_occurrence',
                    'key_list_id',
                    'uuid',
                    'pocket_id',
                    'type',
                    'icon',
                    'icon_url',
                    'archived',
                    'color',
                    'passcode',
                    'key_item_id',
                    'item_id',
                    'file_name',
                    'file_type',
                    'url',
                    'item_name',
                    'comment',
                    'location_latitude',
                    'location_longitude',
                    'location_radius'
                ], [
                    'id' => 'int',
                    'item_id' => 'int',
                    'list_id' => 'int',
                    'contact_id' => 'int',
                    'parent_id' => 'int',
                    'pocket_id' => 'int',
                    'sort' => 'int',
                    'has_children' => 'int',
                    'status' => 'int',
                    'priority' => 'int',
                    'calc_priority' => 'int',
                    'create_datetime' => 'datetime',
                    'update_datetime' => 'datetime',
                    'complete_datetime' => 'datetime',
                    'client_touch_datetime' => 'datetime',
                    'complete_contact_id' => 'int',
                    'due_datetime' => 'datetime',
                    'location_id' => 'int',
                    'amount' => 'float',
                    'assigned_contact_id' => 'int',
                    'favorite' => 'int',
                    'repeat_frequency' => 'int',
                    'repeat_occurrence' => 'int',
                    'archived' => 'int',
                    'key_list_id' => 'int',
                    'key_item_id' => 'int',
                    'location_latitude' => 'float',
                    'location_longitude' => 'float',
                    'location_radius' => 'int'
                ]
            );
            $logs = array_map(function ($_log) use ($action, $entity) {
                if ($action !== pocketlistsLog::ACTION_ADD) {
                    $_log = array_filter($_log, function ($l) {return !(is_null($l) || $l === []);});
                }
                return [
                        'action' => $action,
                        'entity_type' => $entity
                    ] + $_log;
            }, $logs);

            pocketlistsLogService::multipleAdd($logs);
        }
    }
}
