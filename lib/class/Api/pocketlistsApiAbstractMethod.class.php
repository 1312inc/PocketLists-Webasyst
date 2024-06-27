<?php

abstract class pocketlistsApiAbstractMethod extends waAPIMethod
{
    public const METHOD_GET    = 'GET';
    public const METHOD_POST   = 'POST';
    public const METHOD_PUT    = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PATCH  = 'PATCH';

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
        try {
            $dt = new DateTime((string) $sql_dt);
            $dt->setTimezone(new DateTimeZone('UTC'));
            return $dt->format('Y-m-d\TH:i:s.u\Z');
        } catch (Exception $ex) {
            return $sql_dt;
        }
    }
}
