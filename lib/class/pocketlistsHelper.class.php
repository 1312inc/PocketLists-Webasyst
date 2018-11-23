<?php

class pocketlistsHelper
{
    const APP_ID = 'pocketlists';
    const COLOR_DEFAULT = 'blue';

    public static function getDueDatetime(&$date)
    {
        if (!empty($date['due_date']) &&
            !empty($date['due_datetime_hours']) &&
            !empty($date['due_datetime_minutes'])
        ) {
            $tm = strtotime($date['due_date'] . " " . $date['due_datetime_hours'] . ":" . $date['due_datetime_minutes'] . ":00");
            $tm = self::convertToServerTime($tm);
            $date['due_datetime'] = waDateTime::date("Y-m-d H:i:s", $tm);
            unset($date['due_datetime_hours']);
            unset($date['due_datetime_minutes']);
        } else {
            $date['due_datetime'] = null;
        }

        if (!empty($date['due_date'])) {
            $date['due_date'] = date("Y-m-d", strtotime($date['due_date']));
//            waDateTime::parse('date', waDateTime::format('date', $date['due_date']));
        } else {
            $date['due_date'] = null;
        }
    }

    // thanks to hub
    public static function convertToServerTime($timestamp)
    {
        $timezone = wa()->getUser()->getTimezone();
        $default_timezone = waDateTime::getDefaultTimeZone();
        if ($timezone && $timezone != $default_timezone) {
            $date_time = new DateTime(date('Y-m-d H:i:s', $timestamp), new DateTimeZone($timezone));
            $date_time->setTimezone(new DateTimeZone($default_timezone));
            $timestamp = (int)$date_time->format('U');
        }
        return $timestamp;
    }

    public static function calcPriorityOnDueDate($due_date, $due_datetime)
    {
        $now = time();
        $due_status = 0;

        if (!empty($due_date) || !empty($due_datetime)) {
            if (!empty($due_datetime) && $now > strtotime($due_datetime)) { // overdue datetime
                $due_status = 3;
            } elseif (strtotime(date("Y-m-d")) > strtotime($due_date)) { // overdue date
                $due_status = 3;
            } elseif ($due_date == date("Y-m-d")) { // today
                $due_status = 2;
            } elseif ($due_date == date("Y-m-d", $now + 60 * 60 * 24)) { // tomorrow
                $due_status = 1;
            }
        }

        return $due_status;
    }

    public static function getItemChildIds($item_id, $item, &$return)
    {
        $return[] = $item['id'];
        foreach ($item['childs'] as $i) {
            self::getItemChildIds($item_id, $i, $return);
        }
    }

    public static function getListIcons()
    {
//        $path = wa()->getAppPath('img/listicons/', wa()->getApp());
        $icon_ids = array(

            "list",
            "list2",
            "list3",
            "list4",
            "bag",
            "champagne",
            "star",
            "calendar",
            "idea",
            "money",
            "hammok",
            "video",
            "paid-notepad",
            "mission",
            "clock",
            "lock",
            "paid2-checkmark",
            "paid2-chat",
            "paid2-car",
            "bomb",



            "shopping2",
            "bag",
            "cart",
            "grocery",
            "shop",
            "piggy",
            "paid-discount",
            "gift",
            "paid2-ladies-shoe",
            "paid2-sunglasses",
            "tshirt",
            "paid-diamond",
            "barcode",
            "giftcard",
            "color-palette",
            "sale",
            "pl201811-free",
            "pl201811-clothing",
            "pl201811-hanger",
            "pl201811-online",



            "home",
            "paid-flower",
            "cleaning",
            "bath",
            "cactus",
            "people",
            "paid2-keys",
            "paid-coffee",
            "post-box",
            "shower",
            "bird-nest",
            "boot",
            "glove",
            "bucket",
            "plant",
            "paid-safebox",
            "paid-paint",
            "color-wheel",
            "paid-fire-ext",
            "paid-trash",
            "pl201811-bed",
            "pl201811-sofa",
            "pl201811-wateringcan",
            "pl201811-shovel",
            "pl201812-earbuds",
            "pl201812-hat",
            "pl201812-glove",
            "pl201812-jacket",



            "chair",
            "desk-printer",
            "folders",
            "scanner",
            "service",
            "calcuator",
            "diagram",
            "paid-notepad",
            "paid-tie",
            "team",
            "notes1",
            "invoice",
            "construction",
            "delivery",
            "loudspeaker",
            "paid2-boxes",
            "pl201811-docs",
            "pl201811-preza",
            "pl201811-wheelbarrow",
            "pl201811-toolkit",




            "hammok",
            "plane",
            "a-to-b",
            "compass",
            "pl201811-navigator",
            "pl201811-wavetshirt",
            "pl201811-locations",
            "pl201811-hotel",
            "flag1",
            "pointer",
            "paid2-location",
            "paid-map",
            "search-location",
            "vacations",
            "travel",
            "passport",
            "ship",
            "train",
            "helicopter",
            "cabin",




            "paid-sun",
            "paid-umbrella",
            "paid-rain",
            "pl201812-snowcloud",
            "pl201812-snowmountains",
            "pl201812-snowvillage",
            "pl201812-snowshovel",
            "pl201812-snowminus",
            "mission",
            "paid-snow",
            "jungle",
            "paid2-tent",
            "campfire",
            "fishing",
            "paid-klmn",
            "leaf",
            "trekking",
            "diving",
            "rafting",
            "shuttlecock",
            "pl201811-earthlove",
            "pl201811-ecoplant",
            "pl201811-solar",
            "pl201811-plug",



            "pl201812-snowglobe",
            "pl201812-giftcard",
            "pl201812-snowwindow",
            "paid2-snowman",
            "pl201812-candle",
            "pl201812-cupcake",
            "pl201812-risingstar",
            "pl201812-ornament",
            "paid-birthday",
            "wedding",
            "paid-christmas",
            "champagne",
            "church",
            "easter-bunny",
            "easter-egg",
            "antlers",
            "petard",
            "christmas-sack",
            "gift",
            "paid2-christmas-ball",
            "paid2-christmas-stocking",
            "chinese-lantern",
            "hanukkah",
            "ramadan",
            "pl201811-invitations",
            "pl201811-theatre",
            "pl201811-pumpkin",
            "pl201811-cemetery",



            "pl201811-school",
            "pl201811-notebook",
            "pl201811-books",
            "pl201811-atom",
            "blackboard",
            "school-backpack",
            "signs",
            "paid-education",
            "books",
            "ruler-pencil",
            "brush",
            "pencil",
            "globe",
            "paid-alarmclock",
            "bell",
            "route",
            "telescope",
            "paid3-bookmark",
            "glasses",
            "schoolbus",




            "paid3-goal",
            "casino",
            "puzzle",
            "paid-microphone",
            "paid-gamecontroller",
            "paid-music",
            "paid-ufo",
            "paid2-car",
            "palette",
            "video",
            "coins",
            "fishing",
            "billiard",
            "pl201811-camera",
            "paid2-camera",
            "chess",
            "pl201811-sewingmachine",
            "piano",
            "pl201811-speaker",
            "pl201811-spray",




            "paid-carrot",
            "cheese",
            "paid-chicken",
            "paid2-crab",
            "cuttlefish",
            "egg",
            "fish",
            "recipe",
            "steak",
            "paid-apple",
            "paid-eggs",
            "paid-banana",
            "beer",
            "blackberries",
            "paid-bread",
            "paid-cherry",
            "paid-coffee",
            "hamburger",
            "hotdog",
            "paid-icecream",
            "lobster",
            "macaroons",
            "milk",
            "octopus",
            "orange",
            "paid-pear",
            "pineapple",
            "paid-pizza",
            "shrimp",
            "strawberry",
            "tomato",
            "paid-wine-bottle",
            "paid-wine-glass",
            "paid-cocktail",
            "pie",
            "paid-starbucks",





            "pl201811-pills",
            "pl201811-herbal",
            "pl201811-samples",
            "pl201811-cream",
            "medicine",
            "ambulance",
            "blood",
            "doctor",
            "experiment-results",
            "pills",
            "eye-test",
            "stethoscope",
            "strong-heart",
            "tooth",
            "water",
            "paid-thermo",




            "first-prize-medal",
            "award",
            "paid-bicycle",
            "camber-jumping",
            "chronometer",
            "gym",
            "paid-baseball",
            "paid-basketball",
            "paid-football",
            "football",
            "ski",
            "longboard",
            "pl201811-protein",
            "pl201811-treadmill",
            "pl201811-pool",
            "pl201812-skilift",
            "pl201811-shoe",
            "pl201812-snowboard",
            "pl201812-snowskates",
            "pl201812-snowledge",




            "paid-baby",
            "boy-child",
            "paid-babybottle",
            "soska",
            "babytoy",
            "baby-walker",
            "toy-pyramid",
            "abc",
            "toy-ship",
            "toy-bibiaa",
            "toy-octopus",
            "train-baby",
            "baby-foot",
            "paid-baloons",
            "paid-bear",
            "poop",





            "cat",
            "dog",
            "collar",
            "bone",
            "lion",
            "monkey",
            "farm-sheep",
            "turtle",
            "ladybug",
            "crawler",
            "bee",
            "butterfly",
            "parrot",
            "panda",
            "pet-fish",
            "paid-animal",
            "pl201811-bird",
            "pl201811-fox",
            "pl201811-penguin",
            "pl201811-mouse",



            "pl201811-crypto",
            "pl201811-bot",
            "pl201811-trafficlight",
            "pl201811-network",
            "paid2-hotspot",
            "bug-fixing",
            "domain-registration",
            "download",
            "pin-code",
            "paid2-iphone",
            "paid-tv",
            "camera",
            "phone",
            "magnet",
            "power1",
            "alarm",
            "paid-diskette",
            "cable",
            "twitter",
            "facebook",



            "translate",
            "luck",
            "achtung",
            "paid-run",
            "pl201811-dontdisturb",
            "pl201811-parking",
            "pl201811-cmyk",
            "pl201811-rgb",
            "paid-alien",
            "paid2-yinyang",
            "paid3-anchor",
            "paid3-ribbon",
            "paid3-apple",
            "paid2-arrows",
            "nuclear",
            "olympic-rings",
            "david-star",
            "ramadan",
            "shinto",
            "om",




            "flag-abkhazia",
            "flag-algeria",
            "flag-argentina",
            "flag-australia",
            "flag-belarus",
            "flag-belgium",
            "flag-brazil",
            "flag-bulgaria",
            "flag-canada",
            "flag-canary-islands",
            "flag-chile",
            "flag-china",
            "flag-colombia",
            "flag-cuba",
            "flag-cameroon",
            "flag-costa-rica",
            "flag-croatia",
            "flag-cyprus",
            "flag-czech-republic",
            "flag-denmark",
            "flag-egypt",
            "flag-england",
            "flag-estonia",
            "flag-eu",
            "flag-finland",
            "flag-france",
            "flag-georgia",
            "flag-germany",
            "flag-greece",
            "flag-hungary",
            "flag-hongkong",
            "flag-iceland",
            "flag-india",
            "flag-indonesia",
            "flag-israel",
            "flag-italy",
            "flag-jamaica",
            "flag-japan",
            "flag-lithuania",
            "flag-luxembourg",
            "flag-kenya",
            "flag-korea",
            "flag-mexico",
            "flag-moldova",
            "flag-monaco",
            "flag-morocco",
            "flag-namibia",
            "flag-nepal",
            "flag-netherlands",
            "flag-new-zealand",
            "flag-niger",
            "flag-north-korea",
            "flag-norway",
            "flag-palestine",
            "flag-panama",
            "flag-peru",
            "flag-philippines",
            "flag-portugal",
            "flag-poland",
            "flag-republic-of-the-congo",
            "flag-romania",
            "flag-russia",
            "flag-scotland",
            "flag-slovakia",
            "flag-slovenia",
            "flag-sudan",
            "flag-suriname",
            "flag-south-africa",
            "flag-spain",
            "flag-sweden",
            "flag-switzerland",
            "flag-thailand",
            "flag-turkey",
            "flag-uk",
            "flag-ukraine",
            "flag-usa",
            "flag-uruguay",
            "flag-vietnam",
            "flag-venezuela",
            "flag-pirate"


        );
        $icons = array();
        foreach ($icon_ids as $icon_id) {
            $icons[$icon_id] = 'li-' . $icon_id . '@2x.png';
        }

        return $icons;
    }

    private static function compare_last_activity($a, $b)
    {
        $delta = strtotime($b['last_activity']) - strtotime($a['last_activity']);
        if (!$delta) {
            $delta = $a['id'] - $b['id'];
        }
        return $delta;
    }

    public static function getTeammates($teammates_ids, $sort_by_last_activity = true, $exclude_me = true)
    {
        $teammates = array();

        $im = new pocketlistsItemModel();
        $items_count_names = $im->getAssignedItemsCountAndNames($teammates_ids);
        $last_activities = $sort_by_last_activity ? $im->getLastActivities($teammates_ids) : array();
        foreach ($teammates_ids as $tid) {
            if ($exclude_me && $tid == wa()->getUser()->getId()) {
                continue;
            }
            $mate = new waContact($tid);
//            if (!$mate) {
//                continue;
//            }
            $teammates[$tid] = self::getContactData($mate);

            $teammates[$tid]['last_activity'] = isset($last_activities[$tid]) ? $last_activities[$tid] : 0;
            $teammates[$tid]['items_info'] = array(
                'count'        => 0,
                'names'        => "",
                'max_priority' => 0,
            );
            if (isset($items_count_names[$tid])) {
                $teammates[$tid]['items_info'] = array(
                    'count'        => count($items_count_names[$tid]['item_names']),
                    'names'        => implode(', ', $items_count_names[$tid]['item_names']),
                    'max_priority' => $items_count_names[$tid]['item_max_priority'],
                );
            }
        }

        if ($sort_by_last_activity) {
            usort($teammates, array('pocketlistsHelper', "compare_last_activity"));
        }

        // todo: cache
        return $teammates;
    }

    public static function getMonthData($items, $show_month, $month_count = 1)
    {
        $timezone = wa()->getUser()->getTimezone();
        $month_date = waDateTime::date('Y-m', null, $timezone);
        if ($show_month) {
            $month_date = date('Y-m', strtotime(date('Y-m-01') . ' ' . ($show_month . ' month')));
        }
        $month_date = strtotime($month_date);

        $list_colors = array();
        // completed items
        foreach ($items[1] as $item) {
            $list_colors[date('Y-m-d', strtotime($item['complete_datetime']))]['gray'][] = $item['id'];
        }
        foreach ($items[0] as $item) {
            if ($item['due_datetime'] || $item['due_date']) {
                $due_date = date('Y-m-d', strtotime($item['due_date'] ? $item['due_date'] : $item['due_datetime']));
                $item['list_color'] = $item['list_color'] ? $item['list_color'] : 'gray';
                $list_colors[$due_date]['color'][$item['list_color']][] = $item['id'];
            }
        }

        $days = array();
        for ($i = 0; $i < $month_count; $i++) { // 3 month
            $days_count = date('t', $month_date);
            $first_day = date('w', $month_date);
            $last_day = date('w', strtotime(date('Y-m-{$days_count}', $month_date)));

            // first day is 'Sunday'
            if (waLocale::getFirstDay() === 7) {
                $first_day += 1;
                $last_day += 1;
            }
            $first_day = ($first_day === 0) ? 6 : $first_day - 1;
            $last_day = ($last_day === 0) ? 0 : 7 - $last_day;
            $date_start = strtotime('-' . $first_day . ' days', $month_date);
            $date_end = strtotime('+' . ($days_count + $last_day) . ' days', $month_date);

            $current_date_start = $date_start;
            $year = date('Y', $month_date);
            $month_name = date('F', $month_date);
            $month_num = (int)date('n', $month_date);
            $days[$year][$month_name] = array(
                'weeks' => array(),
                'num'   => $month_num,
            );

            do {
                $week = (int)date('W', $current_date_start);
                $day = (int)date('w', $current_date_start);

                if (waLocale::getFirstDay() == 7 && $day == 0) {
                    $week = (int)date('W', strtotime('+1 week', $current_date_start));
                }

                if (!isset($days[$year][$month_name]['weeks'][$week])) {
                    $days[$year][$month_name]['days'][$week] = array();
                }
                $date_date = date('Y-m-d', $current_date_start);
                $hide_other_month_date = false;
                $current_month = date('n', $current_date_start);
                if ($month_num != $current_month) { // hide other month days
                    $hide_other_month_date = true;
                }
                if ($i == 0 && $current_date_start < $month_date) { // but show dates before first month
                    $hide_other_month_date = false;
                }
                if ($i == ($month_count - 1) && $current_date_start > $month_date) { // and after last month
                    $hide_other_month_date = false;
                }
                $days[$year][$month_name]['weeks'][$week][$day] = array(
                    'date'  => array(
                        'day'   => date('j', $current_date_start),
                        'month' => $current_month,
                        'date'  => $date_date,
                    ),
                    'hide'  => $hide_other_month_date,
                    'lists' => array(
                        'color' => isset($list_colors[$date_date]['color']) ?
                            $list_colors[$date_date]['color'] : array(),
                        'gray'  => isset($list_colors[$date_date]['gray']) ?
                            $list_colors[$date_date]['gray'] : array(),
                    ),
//                        isset($list_colors[$date_date]) ? array_keys($list_colors[$date_date]) : array()
                );
                $current_date_start = strtotime('+1 days', $current_date_start);
            } while ($date_end > $current_date_start);

            $month_date = strtotime('+1 month', $month_date);
        }

        return array(
            'days'       => $days,
            'month_date' => $month_date,
        );
    }

    /**
     * @param $contact waContact
     */
    public static function getContactData($contact)
    {
        $default = array(
            'name'      => '(DELETED USER)',
            'username'  => '(DELETED USER)',
            'id'        => 0,
            'photo_url' => '/wa-content/img/userpic96@2x.jpg',
            'userpic'   => '/wa-content/img/userpic20@2x.jpg',
            'status'    => false,
            'teamrole'  => '',
            'login'     => 'deleted',
            'me'        => false,
        );

        if (!$contact->exists()) {
            return $default;
        }

        return array(
            'name'      => $contact->getName(),
            'username'  => $contact->getName(),
            'id'        => $contact->getId(),
            'photo_url' => $contact->getPhoto(),
            'login'     => $contact->get('login'),
            'userpic'   => $contact->getPhoto(20),
            'status'    => $contact->getStatus(),
            'teamrole'  => $contact->get('jobtitle'),
            'me'        => ($contact->getId() == wa()->getUser()->getId()),
        );
    }

    public static function getAccessContactsForPocket($pocket_id = false, $photo_size = 20)
    {
        $wcr = new waContactRightsModel();
        // select users
        $query = "SELECT DISTINCT
                group_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'pocketlists' AND ((name LIKE s:id AND value = 1) OR (name = 'backend' AND value = 2))
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value = 1))";
        $contact_ids = $wcr->query(
            $query,
            array(
                'id' => 'pocket.' . ($pocket_id ? $pocket_id : '%')
            )
        )->fetchAll();
        $contacts = array();
        $groups = array();
        $gm = new waUserGroupsModel();
        foreach ($contact_ids as $id) {
            if ($id['group_id'] < 0) {
                $contact = new waContact(-$id['group_id']);
                $contacts[$contact->getId()] = array(
                    'username' => $contact->getName(),
                    'userpic' => $contact->getPhoto($photo_size)
                );
            } else {
                $groups[] = $id['group_id'];
            }
        }
        $contact_ids = array();
        foreach ($groups as $group_id) {
            $contact_ids = array_merge($contact_ids, $gm->getContactIds($group_id));
        }
        foreach ($contact_ids as $id) {
            $contact = new waContact($id);
            if (!isset($contacts[$contact->getId()])) {
                $contacts[$contact->getId()] = array(
                    'username' => $contact->getName(),
                    'userpic' => $contact->getPhoto($photo_size)
                );
            }
        }
        return $contacts;
    }

    public static function getAccessPocketForContact($contact_id = false)
    {
        $user = $contact_id ? new waContact($contact_id) : wa()->getUser();
        if ($user->isAdmin() || $user->isAdmin('pocketlists')) {
            $pm = new pocketlistsPocketModel();
            $pockets = $pm->getAll('id');
        } else {
            $pockets = $user->getRights('pocketlists', 'pocket.%');

        }
        return $pockets ? array_keys($pockets) : [];
    }

    public static function getAllPocketListsContacts()
    {
        $wcr = new waContactRightsModel();
        $query = "SELECT DISTINCT
                group_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'pocketlists' AND name = 'backend' AND value > 0)
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value > 0)
            ORDER BY group_id ASC";
        $contact_ids = $wcr->query($query)->fetchAll();
        $contacts = array();
        $groups = array();
        $gm = new waUserGroupsModel();
        foreach ($contact_ids as $id) {
            if ($id['group_id'] < 0) { // user
                $contacts[-$id['group_id']] = -$id['group_id'];
            } else { // group
                $groups[] = $id['group_id'];
            }
        }
        $contact_ids = array();
        foreach ($groups as $group_id) {
            $contact_ids = array_merge($contact_ids, $gm->getContactIds($group_id));
        }
        foreach ($contact_ids as $id) {
            $contacts[$id] = $id;
        }
        return $contacts;
    }
}
