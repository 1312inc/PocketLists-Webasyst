<?php

/**
 * Class pocketlistsNaturalInput
 */
class pocketlistsNaturalInput
{
    /**
     * @var pocketlistsNaturalInput
     */
    private static $instance;

    /**
     * @var array
     */

    private static $json_rules = [];

    /**
     * @var array
     */
    private static $numeric_entities = [
        "minutes",
        "hours",
        "days",
        "months",
        "weeks",
        "years",
    ];

    /**
     * pocketlistsNaturalInput constructor.
     */
    protected function __construct()
    {
        $files_path = wa()->getAppPath('/lib/config/data/natural_input/', 'pocketlists');
        $files = [
            $files_path.'natural-input-en.json',
            $files_path.'natural-input-ru.json',
        ];
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

    /**
     * @param $category_name
     *
     * @return bool|int|string
     */
    public static function matchCategory($category_name)
    {
        $instance = self::getInstance();
        $category_name = mb_strtolower(trim($category_name));
        foreach (self::$json_rules as $json_rule) {
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

    /**
     * @param $item_name
     *
     * @return array|bool
     */
    public static function matchPriority($item_name)
    {
        $matches = [];
        if (preg_match('/^(!{1,5})/isu', $item_name, $matches)) {
            $priority = [
                '!'     => 1,
                '!!'    => 2,
                '!!!'   => 3,
                '!!!!'  => 4,
                '!!!!!' => 5,
            ];

            return [
                'name'     => trim(str_replace($matches[1], '', $item_name)),
                'priority' => $priority[$matches[1]],
            ];
        }

        return false;
    }

    /**
     * @param $item_name
     *
     * @return array|bool
     */
    public static function matchNote($item_name)
    {
        $matches = [];
        if (preg_match('/^(.+?)\s\/{2}(.+?)$/isu', $item_name, $matches)) {
            return [
                'name' => trim($matches[1]),
                'note' => trim($matches[2]),
            ];
        }

        return false;
    }

    /**
     * @param $item_name
     *
     * @return array|bool
     */
    public static function matchDueDate(&$item_name)
    {
        $instance = self::getInstance();

        foreach (self::$json_rules as $json_rule) { // all langs
            // todo: first check my own locale
            if (isset($json_rule['smartparse'])) { // all smart phrases
                $lookup_rules = $json_rule['smartparse']['lookup_rules']; // save main rules
                unset($json_rule['smartparse']['lookup_rules']);

                // combine "time" regexes into one long regex
                $smartphrase_long_regexes = $instance->combineSmartparse($json_rule['smartparse']);

                // prepare before parse
                $instance->prepareLookupRules($lookup_rules, $json_rule['smartparse'], $smartphrase_long_regexes);

                // through all main rules (for first found language only)
                $due = $instance->proceedLookupRules(
                    $item_name,
                    $lookup_rules,
                    $smartphrase_long_regexes
                );
                if ($due) {
                    return $due;
                }

            }
        }

        return false;
    }

    /**
     * @param $smartprases
     *
     * @return array
     */
    private static function combineSmartparse($smartprases)
    {
        $smartphrase_long_regexes = [];
        foreach ($smartprases as $smartphrase_name => $smartphrase_value) {
            $tmp = [];
            foreach ($smartphrase_value as $v) {
                $tmp[] = $v[0].(isset($v[2]) ? $v[2] : '');
            }
            $smartphrase_long_regexes[$smartphrase_name] = join('|', $tmp);
        }

        return $smartphrase_long_regexes;
    }

    /**
     * @param $lookup_rules
     * @param $smartphrases
     * @param $smartphrase_long_regexes
     */
    private static function prepareLookupRules(&$lookup_rules, $smartphrases, $smartphrase_long_regexes)
    {
        $instance = self::getInstance();

        // each regex in lookup rule will be replaced with expanded version including smartparses +
        // add new item with matched smartparse`s name
        foreach ($lookup_rules as $lookup_rule_id => $lookup_rule) {
            // skip lookup rule without regexes
            if (!isset($lookup_rule['regex']) || !isset($lookup_rule['rules'])) {
                unset($lookup_rules[$lookup_rule_id]);
                continue;
            }
            // and for each regex inside lookup rule
            foreach ($lookup_rule['regex'] as $regex_in_rule_id => $regex_in_rule) {
                // replace smart placeholders (\number, \week, etc) by combined long regexes
                foreach ($smartphrase_long_regexes as $smartphrase_long_name => $smartphrase_long_regex) {
                    $lookup_rules[$lookup_rule_id]['regex'][$regex_in_rule_id] = str_replace(
                        "\\".$smartphrase_long_name,
                        $smartphrase_long_regex,
                        $lookup_rules[$lookup_rule_id]['regex'][$regex_in_rule_id]
                    );
                }
            }
            foreach ($lookup_rule['rules'] as $rule_in_rule_id => $rule_in_rule) {
                $tmp = explode('|', $rule_in_rule); // smartparse group name
                if (in_array($rule_in_rule[0], self::$numeric_entities)) {
                    $lookup_rules[$lookup_rule_id]['rules'][$rule_in_rule_id] = [
                        $rule_in_rule => &$smartphrases['numeric'],
                    ];
                } elseif (isset($smartphrases[$tmp[0]])) {
                    $lookup_rules[$lookup_rule_id]['rules'][$rule_in_rule_id] = [
                        $rule_in_rule => &$smartphrases[$tmp[0]],
                    ];
                } else {
                    $lookup_rules[$lookup_rule_id]['rules'][$rule_in_rule_id] = [
                        $rule_in_rule => false,
                    ];
                }
            }
        }
    }

    /**
     * @param      $item_name
     * @param      $lookup_rules
     * @param      $smartphrase_long_regexes
     * @param bool $time_after_rules
     *
     * @return array|bool
     */
    private static function proceedLookupRules(
        &$item_name,
        $lookup_rules,
        $smartphrase_long_regexes,
        &$time_after_rules = false
    ) {
        $instance = self::getInstance();

        foreach ($lookup_rules as $lookup_rule) {
            foreach ($lookup_rule['regex'] as $regex_in_rule_id => $regex_in_rule) {
                // found something!!!
                if (preg_match("/".$regex_in_rule."/imu", $item_name, $matches)) {
                    $time_after_rules = $instance->proceedFoundRegex($matches, $lookup_rule, $time_after_rules);
                    if ($time_after_rules) {
                        // trim found date from item's name
                        $item_name_new = trim(str_replace($matches[1], '', $item_name));
                        if (strlen($item_name_new) > 2) { // do not leave lees then 2 letters
                            $item_name = $item_name_new;
                            // and try to search more rules (time?..)
                            $instance->proceedLookupRules(
                                $item_name,
                                $lookup_rules,
                                $smartphrase_long_regexes,
                                $time_after_rules
                            );
                        }
                    }
                    $due = [
                        'due_date'     => date("Y-m-d", $time_after_rules['now']),
                        'due_datetime' => $time_after_rules['usetime'] ?
                            date("Y-m-d H:i:s", $time_after_rules['now']) : null,
                    ];

                    return $due;
                }
            }
        }

        return false;
    }

    /**
     * @param $matches
     * @param $lookup_rule
     * @param $time_after_rules
     *
     * @return array
     */
    private static function proceedFoundRegex($matches, $lookup_rule, $time_after_rules)
    {
        $instance = self::getInstance();

        $reltime_hours = [
            1 => 9,
            2 => 12,
            3 => 18,
        ];
        $relday_days = [
            1 => 1, // tomorrow
            2 => 2, // after tomorrow
            3 => 0 // today
        ];

        $settings = [
            'rules'         => ifset($lookup_rule['rules'], false), // time is calculated based not on current time
            'relative'      => ifset($lookup_rule['relative'], false),
            'checkpasttime' => ifset($lookup_rule['checkpasttime'], false) // check if calculated time is in the past
        ];

        $datetime_now = time();
        $date_now = strtotime(date("Y-m-d 00:00:00"), $datetime_now);
//        $real_now = $lookup_rule_settings['relative'] ? time() : $date_now;
        $now = !empty($time_after_rules['now']) ?
            $time_after_rules['now'] : ($settings['relative'] ? $datetime_now : $date_now);

        $current = [
            'day_number'         => date('j', $datetime_now), // current month day number
            'day_of_week_number' => $instance->getCurrentWeekDay(date('N', $datetime_now)), // current week day number
            'month_number'       => date('n', $datetime_now), // current month number
            'seconds_passed'     => (time() - $date_now),
        ];

        // if user passed hour or minute it will be true
        $time_was_set_by_user = !empty($time_after_rules['usetime']) ? $time_after_rules['usetime'] : false;

        // rules describe how to intreper matched values, in what order, etc
        foreach ($settings['rules'] as $rule_id => $rule) {
            // first match - all phrase, second - captured same all phrase - because of regexp format
            $true_index = $rule_id + 1; // index in rules rule array
//            $rule_id = $rule_id - 1; // index in matched smartparse array
            $smartphrase_regex_value = 0;

            $rule = key($rule);

            $smartparses = isset($lookup_rule['rules'][$rule_id][$rule]) ?
                $lookup_rule['rules'][$rule_id][$rule] : false;

            $rule_mod = explode('|', $rule);
            $rule_mod = end($rule_mod); // get rule smartparse group name or time modificator - always last in array

            // add to current time X hours, days, weeks, months, years
            if (in_array($rule_mod, self::$numeric_entities)) {
                // if this is numeric group
                if ($smartparses) {
                    // for each regex in group
                    foreach ($smartparses as $regex_group) {
                        $time_regex = $regex_group[0].(isset($regex_group[2]) ? $regex_group[2] : '');
                        // try to determine time multiplier from suitable smartphrase regex
                        if (preg_match("/".$time_regex."/imu", $matches[$true_index])) {
                            $smartphrase_regex_value = $regex_group[1];
                            break;
                        }
                    }
                } else {
                    $smartphrase_regex_value = (int)$matches[$true_index];
                }
                if ($matches[$true_index] === "") {
                    $smartphrase_regex_value = 1;
                }
                if (!$settings['relative'] && !in_array($rule_mod, ["hours", "weeks", "minutes"])) {
                    $smartphrase_regex_value--;
                }
                $now = strtotime("+ ".$smartphrase_regex_value." ".$rule_mod, $now);
                // way to tell futher rules, that time was set by user in item's name
                if (in_array($rule_mod, ["hours", "minutes"])) {
                    $time_was_set_by_user = true;
                }
            } elseif ($rule_mod == "week") {
                // for each regex in group
                foreach ($smartparses as $regex_group) {
                    $time_regex = $regex_group[0].(isset($regex_group[2]) ? $regex_group[2] : '');
                    // try to determine time multiplier from suitable smartphrase regex
                    if (preg_match("/".$time_regex."/imu", $matches[$true_index])) {
                        $smartphrase_regex_value = $regex_group[1];
                        break;
                    }
                }
//                $now += abs($current['day_of_week_number'] - $smartphrase_regex_value) * $secs_in['day'];
                $days = $smartphrase_regex_value - $current['day_of_week_number'];
                if ($days < 0) {
                    $days = 7 + $days;
                }
                $now = strtotime("+".$days." days", $now);
                // and reset to the beging of the day
                $now = strtotime(date("Y-m-d 00:00:00", $now));
//                    $now -= $current['seconds_passed'];
            } elseif ($rule_mod == "next") { // only week for now
                // for each regex in group
                foreach ($smartparses as $regex_group) {
                    $time_regex = $regex_group[0].(isset($regex_group[2]) ? $regex_group[2] : '');
                    // try to determine time multiplier from suitable smartphrase regex
                    if (preg_match("/".$time_regex."/imu", $matches[$true_index])) {
                        $smartphrase_regex_value = $regex_group[1];
                        break;
                    }
                }
                // determine current week day number
                $days = abs($current['day_of_week_number'] - $smartphrase_regex_value) + 7;
                $now = strtotime("+".$days." days", $now);
//                $now += abs($current['day_of_week_number'] - $smartphrase_regex_value) * $secs_in['day'] + $secs_in['week'];
            } elseif ($rule_mod == "month") {
                // for each regex in group
                foreach ($smartparses as $regex_group) {
                    $time_regex = $regex_group[0].(isset($regex_group[2]) ? $regex_group[2] : '');
                    // try to determine time multiplier from suitable smartphrase regex
                    if (preg_match("/".$time_regex."/imu", $matches[$true_index])) {
                        $smartphrase_regex_value = $regex_group[1];
                        break;
                    }
                }
                $month_to_add = $smartphrase_regex_value - $current['month_number'];
                if ($month_to_add < 0) {
                    $month_to_add = 12 + $month_to_add;
                }
//                    if (!$lookup_rule_settings['relative']) {
//                        $month_to_add = $month_to_add - 1;
//                    }
                $now = strtotime("+ ".$month_to_add." months", $now);
                // will substruct all days in month to get first day in month
                $now = strtotime(date("Y-m-1", $now));
//              $now -= strtotime("+ " . $current['day_number'] . " days", $now);($current['day_number'] * $secs_in['day']);
            } elseif ($rule_mod == "year") {
                $now = strtotime("01.01.".$matches[$true_index]);
            } elseif ($rule_mod == "reltime") {
// for each regex in group
                foreach ($smartparses as $regex_group) {
                    $time_regex = $regex_group[0].(isset($regex_group[2]) ? $regex_group[2] : '');
                    // try to determine time multiplier from suitable smartphrase regex
                    if (preg_match("/".$time_regex."/imu", $matches[$true_index])) {
                        $smartphrase_regex_value = $regex_group[1];
                        break;
                    }

                }
                if ($time_was_set_by_user) { // if there was hours in item's name
                    if ($smartphrase_regex_value === 0 ||
                        $smartphrase_regex_value === 2 ||
                        $smartphrase_regex_value === 3
                    ) { // day, evening
                        $now = strtotime("+".($reltime_hours[$smartphrase_regex_value] + 12)." hours", $now);
                    }
                } else { // if not
                    // reset to the begining of the day
                    $now = strtotime(date("Y-m-d 00:00:00", $now));
                    // and just add predefined hours
                    $now = strtotime("+".$reltime_hours[$smartphrase_regex_value]." hours", $now);
                }
                $time_was_set_by_user = true;
            } elseif ($rule_mod == "relday") {
                // for each regex in group
                foreach ($smartparses as $regex_group) {
                    $time_regex = $regex_group[0].(isset($regex_group[2]) ? $regex_group[2] : '');
                    // try to determine time multiplier from suitable smartphrase regex
                    if (preg_match("/".$time_regex."/imu", $matches[$true_index])) {
                        $smartphrase_regex_value = $regex_group[1];
                        // break will broke "послезавтра" matching - it will match "завтра"
//                        break;
                    }
                }
                if (isset($relday_days[$smartphrase_regex_value])) {
                    $now = strtotime("+".$relday_days[$smartphrase_regex_value]." days", $now);
                    if (!$settings['relative']) { // reset day time
                        $now = strtotime(date("Y-m-d 00:00:00", $now));
                    }
                }
            } elseif ($rule_mod == "am") {
                $hours_count = date("G", $now);
                if ($hours_count > 12) {
                    $now = strtotime("-12 hours", $now);
                }
            } elseif ($rule_mod == "pm") {
                $hours_count = date("G", $now);
                if ($hours_count >= 0 && $hours_count < 12) {
                    $now = strtotime("+12 hours", $now);
                }
            }
        }
        // due can't be in past, so will check this and fix
        if ($settings['checkpasttime']) {
            if ($now <= $datetime_now) {
                $now = strtotime("+1 ".$settings['checkpasttime'], $now);
            }
        }

        return [
            'usetime' => $time_was_set_by_user,
            'now'     => $now,
        ];
    }

    /**
     * @param $day
     *
     * @return int
     */
    private static function getCurrentWeekDay($day)
    {
        // first day is not 'Sunday'
        if (waLocale::getFirstDay() != 7) {
            $day += 1;
        }

        return ($day > 7) ? 1 : $day;
    }

    /**
     * @param $strings
     */
    public static function testMatchDueDate($strings)
    {
        if (!is_array($strings)) {
            $strings = [$strings];
        }
        foreach ($strings as $string) {
            print_r(
                [
                    'string'      => $string,
                    'server_date' => date("Y-m-d H:i:s"),
                ] +
                self::matchDueDate($string) +
                ['string_after' => $string]
            );
        }
    }

    /**
     * @param      $string
     * @param bool $encode
     *
     * @return mixed|string
     * @throws waException
     */
    public static function matchLinks($string, $encode = true)
    {
        $pattern = '(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
        $replace = [];

        $i = 0;
        while (preg_match(
            '/'.$pattern.'/miu',
            $string,
            $matches
        )
        ) {
            $i++;
            $now = time();
            $replace_key = "###{$i}1312{$now}WILLBEREPLACEDWITHLINK{$now}1312{$i}###";
            $string = str_replace($matches[1], $replace_key, $string);
            $replace[$replace_key] = self::replaceWithLink($matches[1]);
        }

        if ($encode) {
            $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }

        $string = str_replace(array_keys($replace), $replace, $string);

        return $string;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public static function getLinks($string)
    {
        $pattern = '(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

        $matchedLinks = [];

        while (preg_match('/'.$pattern.'/miu', $string, $matches)) {
            $string = str_replace($matches[1], '', $string);
            $matchedLinks[] = $matches[1];
        }

        return $matchedLinks;
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws waException
     */
    public static function replaceWithLink($url)
    {
        static $linkDeterminator;
        if ($linkDeterminator === null) {
            $linkDeterminator = new pocketlistsLinkDeterminer();
        }

        $icon = '';
        $linkData = $linkDeterminator->getAppTypeId($url);
        if ($linkData) {
            $icon = $linkDeterminator->getAppIcon($linkData['app']).' ';
        }

        if (strpos($url, 'http') === 0) {
            return '<a href="'.$url.'" target="_blank">'.$icon.$url.'</a>';
        }

        return '<a href="http://'.$url.'" target="_blank">'.$icon.$url.'</a>';
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function removeTags($string)
    {
        return strip_tags($string);
    }
}
