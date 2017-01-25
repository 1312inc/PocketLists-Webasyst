<?php

class pocketlistsLogAction
{
    const LIST_CREATED = 'list_created';
    const LIST_DELETED = 'list_deleted';
    const LIST_ARCHIVED = 'list_archived';
    const NEW_ITEMS = 'new_items';
    const ITEM_ASSIGN = 'item_assign';
    const ITEM_COMPLETED = 'item_completed';
    const ITEM_COMMENT = 'item_comment';

    private static $data;
    private static $app_url;
    private static $logs;

    public static function getActions()
    {
        return array(
            self::LIST_CREATED   => array(
                'name' => /*_w*/
                    ('created new list'),
            ),
            self::LIST_DELETED   => array(
                'name' => /*_w*/
                    ('deleted a list'),
            ),
            self::LIST_ARCHIVED  => array(
                'name' => /*_w*/
                    ('archived'),
            ),
            self::NEW_ITEMS      => array(
                'name' => /*_w*/
                    ('added new items to'),
            ),
            self::ITEM_COMPLETED => array(
                'name' => /*_w*/
                    ('completed'),
            ),
            self::ITEM_COMMENT   => array(
                'name' => /*_w*/
                    ('commented item in'),
            ),
            self::ITEM_ASSIGN    => array(
                'name' => /*_w*/
                    ('assigned list item to'),
            ),
        );
    }

    public static function explainLogs($logs)
    {
        self::$logs = wa()->getConfig()->explainLogs($logs);
        self::$app_url = wa()->getConfig()->getBackendUrl(true) . 'pocketlists/';

        foreach (self::$logs as $log_id => $log_entry) {
            self::$logs[$log_id]['params_html'] = '';
            $action = $log_entry['action'];
            if (intval($log_entry['params'])) {
                self::$data = $log_entry['params'];
            } else {
                self::$data = json_decode($log_entry['params'], true);
            }
            try {
                self::$logs[$log_id]['params_html'] = self::$action($log_id);
            } catch (waException $ex) {
                self::$logs[$log_id]['params_html'] = 'no action for ' . $action;
            }
        }
        return self::$logs;
    }

    private static function list_created()
    {
        $list_url = self::$app_url . '#/list/' . self::$data['id'] . '/';
        $list_name = htmlspecialchars(self::$data['name'], ENT_QUOTES);
        return "<a href=\"{$list_url}\">{$list_name}</a>";
    }

    private static function list_deleted()
    {
        return "";
    }

    private static function list_archived()
    {
        return self::getListUrlHtml(self::$data['list_id']);
    }

    private static function new_items()
    {
        if (self::$data['id']) {
            $list_url = self::$app_url . '#/list/' . self::$data['id'] . '/';
            $list_name = htmlspecialchars(self::$data['name'], ENT_QUOTES);
            return "<a href=\"{$list_url}\">{$list_name}</a>";
        } else {
            return _w("to his personal to-do stream");
        }
    }

    private static function item_completed()
    {
        return htmlspecialchars(self::$data['name']);
    }

    private static function item_comment()
    {
        return self::getListUrlHtml(self::$data['list_id']);
    }

    private static function item_assign()
    {
        $contact = new waContact(self::$data['assigned_to']);
        return $contact->getName() . ": " . self::getListUrlHtml(self::$data['list_id']);
    }

    private static function getListUrlHtml($list_id)
    {
        $lm = new pocketlistsListModel();
        $list = $lm->getById($list_id);
        $list_url = self::$app_url . '#/list/' . $list['id'] . '/';
        $list_name = htmlspecialchars($list['name'], ENT_QUOTES);
        return "<a href=\"{$list_url}\">{$list_name}</a>";
    }

}
