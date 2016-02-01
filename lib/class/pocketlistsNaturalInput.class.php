<?php

class pocketlistsNaturalInput
{
    private static $instance;
    private static $json_rules = array();

    public function __construct()
    {
        $files_path = wa()->getAppPath().'/lib/config/data/natural_input/';
        $files = array(
            $files_path.'natural-input-en.json',
            $files_path.'natural-input-ru.json'
        );
        foreach ($files as $file) {
            if (file_exists($file)) {
                self::$json_rules[] = json_decode(file_get_contents($file), true);
            }
        }
    }

    /**
     *
     * @return pocketlistsNaturalInput
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function matchCategory($category_name)
    {
        $category_name = mb_strtolower(trim($category_name));
        $instance = self::getInstance();
        foreach ($instance::$json_rules as $json_rule) {
            if (isset($json_rule['task_categs'])) {
                foreach ($json_rule['task_categs'] as $flag_id => $flag_strings) {
                    foreach ($flag_strings as $flag_string) {
                        if (strpos($category_name, $flag_string) !== false) {
                            return $flag_id;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function matchPriority($item_name)
    {
        $matches = array();
        if (preg_match('/^(!{1,3})(.+?)$/is', $item_name, $matches)) {
            $priority = array(
                '!' => 1,
                '!!' => 2,
                '!!!' => 3
            );
            return array(
                'name' => trim($matches[2]),
                'priority' => $priority[$matches[1]]
            );
        }
        return false;
    }
}