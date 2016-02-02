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

    public static function matchNote($item_name)
    {
        $matches = array();
        if (preg_match('/^(.+?)\/{2}(.+?)$/is', $item_name, $matches)) {
            return array(
                'name' => trim($matches[1]),
                'note' => trim($matches[2])
            );
        }
        return false;
    }

    public static function matchDueDate($item_name)
    {
        $instance = self::getInstance();
        foreach ($instance::$json_rules as $json_rule) {
            if (isset($json_rule['smartparse'])) {
                $lookup_rules = $json_rule['smartparse']['lookup_rules'];
                unset($json_rule['smartparse']['lookup_rules']);

                // combine "time" regexes
                $timegroup_regexes = array();
                foreach ($json_rule['smartparse'] as $smart_name => $smart_value) {
                    $tmp = array();
                    foreach ($smart_value as $v) {
                        if (isset($v[2])) {
                            $tmp[] = $v[0].$v[2];
                        } else {
                            $tmp[] = $v[0];
                        }
                    }
                    $timegroup_regexes[$smart_name] = join('|', $tmp);
                }

                foreach ($lookup_rules as $lookup_rule) {
                    foreach ($lookup_rule['regex'] as $regex) {
                        // replace smart placeholders by regexes
                        $matched_groups = array(); // for matched time groups
                        foreach ($timegroup_regexes as $time_regex_name => $time_regex_value) {
                            $new_regex = str_replace("\\".$time_regex_name, $time_regex_value, $regex);
                            // if replaces - save such group for futher time calculation
                            if (strcmp($regex, $new_regex) !== 0) {
                                $matched_groups[] = $time_regex_name;
                                $regex = $new_regex;
                            }
                        }
                        if (preg_match("/".$regex."/imu", $item_name, $matches)) {
                            // what time unit

                            // time unit value

                            break;
                        }
                    }
                }
            }
        }
    }
}