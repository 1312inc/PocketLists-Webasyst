<?php

class pocketlistsActivity
{
    private static $expire_time = 180;
    private static $instance;
    private static $app_id = 'pocketlists';

    protected function __construct($time = null)
    {
        $this->data = wa()->getStorage()->get(__CLASS__);
        if (!$this->data) {
            $this->data = array();
        }
        $this->timestamp = time();
        $this->timestamp_activity = strtotime(self::getUserActivity());

        foreach ($this->data as &$timestamps) {
            $filtered_timestamps = array_filter($timestamps, array($this, 'filter'));
            if (!count($filtered_timestamps)) {

                $id = max(array_keys($timestamps));
                if ($this->timestamp_activity && (($this->timestamp_activity - $timestamps[$id]) < self::$expire_time)) {
                    $filtered_timestamps[$id] = $timestamps[$id];
                }
            }
            $timestamps = $filtered_timestamps;
            unset($timestamps);
        }
    }

    /**
     *
     * Get object instance
     * @param int $time
     * @return pocketlistsActivity
     */
    public static function getInstance($time = null)
    {
        if (!is_object(self::$instance)) {
            self::$instance = new self($time);
        }
        return self::$instance;
    }

    public function set($path, $id = array())
    {
        if ($id) {
            if (!isset($this->data[$path])) {
                $this->data[$path] = array();
            }
            if (is_array($id)) {
                $this->data[$path][min($id)] = $this->timestamp;
                $this->data[$path][max($id)] = $this->timestamp;
            } else {
                $this->data[$path][$id] = $this->timestamp;
            }
        }
        return $this->data[$path];
    }

    public static function getUserActivity($id = null, $sidebar = true)
    {
        $storage = wa()->getStorage();
        $app_last_datetime = $storage->get(self::$app_id . "_last_datetime");
        if ($id || (is_null($id) && ($id = wa()->getUser()->getId()))) {
            $contact = new waContactSettingsModel();
            $result = $contact->get($id, self::$app_id);

            if (!$app_last_datetime) {
                $app_last_datetime = isset($result[self::$app_id . "_last_datetime"]) ? $result[self::$app_id . "_last_datetime"] : self::setUserActivity($id);
            }

            $storage->set(self::$app_id . "_last_datetime", $app_last_datetime);
        }
        return $app_last_datetime;
    }

    /**
     * Update timestamp of user activity at application
     * @param null $id
     * @param bool $force
     * @return bool|null|string
     */
    public static function setUserActivity($id = null, $force = false)
    {
        if (is_null($id)) {
            $id = wa()->getUser()->getId();
        }
        $t = null;
        $storage = wa()->getStorage();
        $app_last_datetime = $storage->get(self::$app_id . "_last_datetime");
        if (!$force && (!$app_last_datetime || ((time() - strtotime($app_last_datetime)) > 120))) {
            $force = 1;
        }
        if ($force) {
            $t = date("Y-m-d H:i:s", time() + 1);
            $contact = new waContactSettingsModel();
            $contact->set($id, self::$app_id, self::$app_id . "_last_datetime", $t);
            $storage->write(self::$app_id . "_last_datetime", $t);
        }
        return $t;
    }
}