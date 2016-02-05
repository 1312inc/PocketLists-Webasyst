<?php

class pocketlistsNaturalInput
{
    private static $instance;
    private static $json_rules = array();

    public function __construct()
    {
        $files_path = wa()->getAppPath() . '/lib/config/data/natural_input/';
        $files = array(
            $files_path . 'natural-input-en.json',
            $files_path . 'natural-input-ru.json'
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

        $secs_in = array(
            'hour' => 60 * 60,
            'day'  => 60 * 60 * 24,
            'week' => 60 * 60 * 24 * 7,
        );
        $reltime_secs = array(
            1 => $secs_in['hour'] * 9, // 9 o'clock
            2 => $secs_in['hour'] * 12, // 12 o'clock
            3 => $secs_in['hour'] * 18 // 18 o'clock
        );
        $relday_secs = array(
            1 => $secs_in['day'] * 1, // tomorrow
            2 => $secs_in['day'] * 2, // after tomorrow
            3 => 0 // today
        );
        foreach ($instance::$json_rules as $json_rule) { // all langs
            if (isset($json_rule['smartparse'])) { // all smart phrases
                $lookup_rules = $json_rule['smartparse']['lookup_rules']; // save main rules
                unset($json_rule['smartparse']['lookup_rules']);

                // combine "time" regexes into one long regex
                $smartphrase_long_regexes = array();
                foreach ($json_rule['smartparse'] as $smartphrase_name => $smartphrase_value) {
                    $tmp = array();
                    foreach ($smartphrase_value as $v) {
                        $tmp[] = $v[0] . (isset($v[2]) ? $v[2] : '');
                    }
                    $smartphrase_long_regexes[$smartphrase_name] = join('|', $tmp);
                }

                // through all main rules
                foreach ($lookup_rules as $lookup_rule) {
                    if (!isset($lookup_rule['regex'])) {
                        continue;
                    }
                    // and for each regex inside main rule
                    foreach ($lookup_rule['regex'] as $regex_in_rule) {
                        // replace smart placeholders (\number, \week, etc) by combined long regexes
                        $matched_smartphrase_groups = array(); // for matched smartphrase groups
                        foreach ($smartphrase_long_regexes as $smartphrase_long_name => $smartphrase_long_regex) {
                            $new_regex = str_replace("\\" . $smartphrase_long_name, $smartphrase_long_regex, $regex_in_rule);
                            // if replaces - save such group for futher time calculation
                            if (strcmp($regex_in_rule, $new_regex) !== 0) {
                                $matched_smartphrase_groups[] = $smartphrase_long_name;
                                $regex_in_rule = $new_regex;
                            }
                        }
                        // found something!!!
                        if (preg_match("/" . $regex_in_rule . "/imu", $item_name, $matches)) {
                            $the_date = false;

                            $lookup_rule_rules = ifset($lookup_rule['rules'], false);
                            $lookup_rule_settime = ifset($lookup_rule['settime'], null);
                            $lookup_rule_absolute = ifset($lookup_rule['absolute'], null);
                            $lookup_rule_notrim = ifset($lookup_rule['notrim'], null);

                            $date_now = strtotime(date("Y-m-d"));
                            $real_now = $lookup_rule_settime ? time() : $date_now;
                            $now = $real_now;

                            $current = array(
                                'day_number' => date('j', $real_now), // determine current month day number
                                'day_of_week_number' => $instance::getCurrentWeekDay(date('N', $real_now)), // determine current week day number
                                'month_number' => date('m', $real_now), // determine current month number
                                'seconds_passed' => (time() - $date_now),
                            );

                            // rules describe how to intreper matched values, in what order, etc
                            foreach ($lookup_rule_rules as $lookup_rule_rule_regex_id => $lookup_rule_rule) {
                                $true_index = $lookup_rule_rule_regex_id + 1; // first match - all phrase, second - captured same all phrase - because of regexp format
                                $true_index_match_group = $lookup_rule_rule_regex_id - 1; // first match - all phrase, second - captured same all phrase - because of regexp format
                                $smartphrase_regex_value = 0;
                                // for each found group
//                                foreach ($matched_smartphrase_groups as $matched_smartphrase_group) {
//
//
//                                    $i++;
//                                }


                                switch ($lookup_rule_rule) {
                                    // add to current time X hours, days, weeks, months, years
                                    case "hours":
                                    case "days":
                                    case "weeks":
                                    case "months":
                                    case "years":
                                        // if this is numeric group
                                        if (isset($matched_smartphrase_groups[$true_index_match_group]) &&
                                            $matched_smartphrase_groups[$true_index_match_group] === 'numeric'
                                        ) {
                                            // for each regex in group
                                            foreach ($json_rule['smartparse']['numeric'] as $regex_group) {
                                                $time_regex = $regex_group[0] . (isset($regex_group[2]) ? $regex_group[2] : '');
                                                // try to determine time multiplier from suitable smartphrase regex
                                                if (preg_match("/" . $time_regex . "/imu", $matches[$true_index])) {
                                                    $smartphrase_regex_value = $regex_group[1];
                                                    break;
                                                }
                                            }
                                            if ($matches[$true_index] === "") {
                                                $smartphrase_regex_value = 1;
                                            }
                                        } else {
                                            $smartphrase_regex_value = (int) $matches[$true_index];
                                        }
                                        if ($lookup_rule_absolute && !in_array($lookup_rule_rule, array("hours", "weeks"))) {
                                            $smartphrase_regex_value--;
                                        }
                                        $now = strtotime("+ " . $smartphrase_regex_value . " " . $lookup_rule_rule, $now);
                                        break;
                                    case "minute":
                                        break;
                                    case "hour":
                                        break;
                                    case "week":
                                        // for each regex in group
                                        foreach ($json_rule['smartparse']['week'] as $regex_group) {
                                            $time_regex = $regex_group[0] . (isset($regex_group[2]) ? $regex_group[2] : '');
                                            // try to determine time multiplier from suitable smartphrase regex
                                            if (preg_match("/" . $time_regex . "/imu", $matches[$true_index])) {
                                                $smartphrase_regex_value = $regex_group[1];
                                                break;
                                            }
                                        }
//                                        $now += abs($current['day_of_week_number'] - $smartphrase_regex_value) * $secs_in['day'];
                                        $days = abs($current['day_of_week_number'] - $smartphrase_regex_value);
                                        $days = $days === 0 ? 7 : $days; // if new date equals current - +1 week
                                        $now = strtotime("+" . $days . " days", $now);
                                        // and reset to the beging of the day
                                        $now -= $current['seconds_passed'];
                                        break;
                                    case "next week":
                                        // for each regex in group
                                        foreach ($json_rule['smartparse']['week'] as $regex_group) {
                                            $time_regex = $regex_group[0] . (isset($regex_group[2]) ? $regex_group[2] : '');
                                            // try to determine time multiplier from suitable smartphrase regex
                                            if (preg_match("/" . $time_regex . "/imu", $matches[$true_index])) {
                                                $smartphrase_regex_value = $regex_group[1];
                                                break;
                                            }
                                        }
                                        // determine current week day number
                                        $days = abs($current['day_of_week_number'] - $smartphrase_regex_value) + 7;
                                        $now = strtotime("+" . $days . " days", $now);
//                                        $now += abs($current['day_of_week_number'] - $smartphrase_regex_value) * $secs_in['day'] + $secs_in['week'];
                                        break;
                                    case "month":
                                        // for each regex in group
                                        foreach ($json_rule['smartparse']['month'] as $regex_group) {
                                            $time_regex = $regex_group[0] . (isset($regex_group[2]) ? $regex_group[2] : '');
                                            // try to determine time multiplier from suitable smartphrase regex
                                            if (preg_match("/" . $time_regex . "/imu", $matches[$true_index])) {
                                                $smartphrase_regex_value = $regex_group[1];
                                                break;
                                            }
                                        }
//                                        if ($lookup_rule_absolute) { // just add monthы
//                                            $month_to_add = $smartphrase_regex_value - 1;
//                                        } else { // if not - if there are some months already
                                        $month_to_add = $smartphrase_regex_value - $current['month_number'];
                                        if ($month_to_add <= 0) {
                                            $month_to_add = 12 + $month_to_add;
                                        }
//                                        $month_to_add = $t == 0 ? 12 : $t;
//                                        }
                                        $now = strtotime("+ " . $month_to_add . " months", $now);
                                        // will substruct all days in month to get first day in month
//                                        if ($lookup_rule_absolute) {
                                            $now = strtotime(date("Y-m-1", $now));
//                                            $now -= strtotime("+ " . $current['day_number'] . " days", $now);($current['day_number'] * $secs_in['day']);
//                                        }
                                        break;
                                    case "year":
                                        $now = strtotime("01.01." . $matches[$true_index]);
                                        break;
//                                    case "day of month":
//                                         add days to month in $now
//                                        $now = strtotime("+" . $smartphrase_regex_value . "days", $now);
//                                        break;
                                    case "time of day":
                                        // for each regex in group
                                        foreach ($json_rule['smartparse']['reltime'] as $regex_group) {
                                            $time_regex = $regex_group[0] . (isset($regex_group[2]) ? $regex_group[2] : '');
                                            // try to determine time multiplier from suitable smartphrase regex
                                            if (preg_match("/" . $time_regex . "/imu", $matches[$true_index])) {
                                                $smartphrase_regex_value = $regex_group[1];
                                                break;
                                            }

                                        }
                                        if (date('G', $now)) { // if there are some hours already
                                            if ($smartphrase_regex_value === 0 ||
                                                $smartphrase_regex_value === 2 ||
                                                $smartphrase_regex_value === 3
                                            ) { // day, evening
                                                $now += ($reltime_secs[$smartphrase_regex_value] + 12 * $secs_in['hour']);
                                            }
                                        } else { // if not - just add predefined hours
                                            $now += $reltime_secs[$smartphrase_regex_value];
                                        }
                                        break;
                                    case "time of week":
                                        // for each regex in group
                                        foreach ($json_rule['smartparse']['relday'] as $regex_group) {
                                            $time_regex = $regex_group[0] . (isset($regex_group[2]) ? $regex_group[2] : '');
                                            // try to determine time multiplier from suitable smartphrase regex
                                            if (preg_match("/" . $time_regex . "/imu", $matches[$true_index])) {
                                                $smartphrase_regex_value = $regex_group[1];
                                                break;
                                            }
                                        }
                                        if (isset($relday_secs[$smartphrase_regex_value])) {
                                            $now += $relday_secs[$smartphrase_regex_value];
                                            if ($lookup_rule_settime) { // reset day time
                                                $now -= $current['seconds_passed'];
                                            }
                                        }
                                        break;
                                }
                            }
                            return array(
                                'due_date' => date("Y-m-d", $now),
                                'due_datetime' => $lookup_rule_settime ? date("Y-m-d H:i:s", $now) : null
                            );
//                            break 2;
                        }
                    }
                }
            }
        }
        return false;
    }

    private static function getCurrentWeekDay($day)
    {
        // first day is not 'Sunday'
        if (waLocale::getFirstDay() != 7) {
            $day += 1;
        }
        return ($day > 7) ? 1 : $day;
    }
}