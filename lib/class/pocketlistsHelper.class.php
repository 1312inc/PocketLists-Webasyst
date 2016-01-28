<?php

class pocketlistsHelper
{
    public static function getAccessContactsForPocket($pocket_id = false, $photo_size = 20)
    {
        $wcr = new waContactRightsModel();
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
        foreach ($contact_ids as $id) {
            $contact = new waContact(-$id['group_id']);
            $contacts[$contact->getId()] = array(
                'username' => $contact->getName(),
                'userpic' => $contact->getPhoto($photo_size)
            );
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
        return $pockets ? array_keys($pockets) : false;
    }

    public static function getAllPocketListsContacts()
    {
        $wcr = new waContactRightsModel();
        $query = "SELECT DISTINCT
                -1*group_id user_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'pocketlists' AND name = 'backend' AND value > 0)
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value > 0)
            ORDER BY user_id ASC";
        return array_keys($wcr->query($query)->fetchAll('user_id'));
    }

    public static function isAdmin()
    {
        static $result = null;
        if ($result === null) {
            $result = wa()->getUser()->getRights('pocketlists', 'backend') > 1;
        }
        return $result;
    }

    public static function getDueDatetime(&$date)
    {
        if (!empty($date['due_date']) &&
            !empty($date['due_datetime_hours']) &&
            !empty($date['due_datetime_minutes'])
        ) {
            $date['due_datetime'] = waDateTime::date(
                "Y-m-d H:i:s",
                strtotime($date['due_date'] . " " . $date['due_datetime_hours'] . ":" . $date['due_datetime_minutes'] . ":00")
            );
            unset($date['due_datetime_hours']);
            unset($date['due_datetime_minutes']);
        } else {
            $date['due_datetime'] = null;
        }

        $date['due_date'] = !empty($date['due_date']) ? waDateTime::parse('date', waDateTime::format('date', $date['due_date'])) : null;
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
            "achtung",
            "books",
            "calendar",
            "construction",
            "gift",
            "grocery",
            "gym",
            "idea",
            "invoice",
            "medicine",
            "money",
            "passport",
            "recipe",
            "star",
            "travel",
            "vacations",

            "award",
            "bag",
            "calcuator",
            "cart",
            "checkout",
            "clock",
            "coins",
            "contact",
            "delivery",
            "diagram",
            "home",
            "list2",
            "lock",
            "palette",
            "pencil",
            "phone",
            "piggy",
            "plane",
            "service",
            "tools",
            "video",
            "work",
            "calendar-notavailable",

            "paid-christmas",
            "paid2-christmas-ball",
            "paid2-christmas-stocking",
            "paid2-santa",
            "paid2-snowman",
            "paid2-gingerbread",
            "paid2-mistletoe",
            "paid-birthday",
            "paid-baloons",

            "paid-map",
            "paid2-location",
            "paid-music",
            "paid-microphone",
            "paid-newspaper",
            "paid-notepad",
            "paid-tie",
            "paid2-car",
            "paid2-chat",
            "paid2-clock",
            "paid2-iphone",
            "paid2-keys",
            "paid2-sunglasses",
            "paid2-yinyang",
            "paid2-camera",
            "paid2-hotspot",
            "paid2-ladies-shoe",
            "paid2-checkmark",
            "paid2-landscape",
            "paid2-guitar",
            "paid2-tent",
            "paid2-crab",
            "paid2-boxes",
            "paid2-arrows",
            "paid-run",
            "paid-bicycle",
            "paid-baseball",
            "paid-basketball",
            "paid-football",

            "paid-avocado",
            "paid-banana",
            "paid-apple",
            "paid-pear",
            "paid-corn",
            "paid-watermelon",
            "paid-carrot",
            "paid-pizza",
            "paid-steak",
            "paid-icecream",
            "paid-lollipops",
            "paid-spaghetti",
            "paid-chicken",
            "paid-eggs",
            "paid-bread",
            "paid-candy",
            "paid-cherry",
            "paid-coffee",
            "paid-starbucks",
            "paid-cocktail",
            "paid-whisky-bottle",
            "paid-wine-bottle",
            "paid-wine-glass",

            "paid-rain",
            "paid-cloud-sun",
            "paid-sun",
            "paid-snow",
            "paid-thunderstorm",
            "paid-umbrella",
            "paid-alarmclock",

            "paid-baby",
            "paid-babybottle",
            "paid-bear",
            "paid-dogcat",
            "paid-animal",
            "paid-armchair",
            "paid-bed",
            "paid-chestofdraws",
            "paid-desk",
            "paid-diamond",
            "paid-discount",
            "paid-dj",
            "paid-diskette",
            "paid-education",
            "paid-fire-ext",
            "paid-flashlight",
            "paid-flower",
            "paid-gameboy",
            "paid-gamecontroller",
            "paid-globe",
            "paid-saturn",
            "paid-ufo",
            "paid-alien",
            "paid-hammer",
            "paid-hanger",
            "paid-wardrobe",
            "paid-tv",
            "paid-imac",
            "paid-safebox",
            "paid-scissors",
            "paid-sock",
            "paid-paint",
            "paid-paintbrush",
            "paid-klmn",
            "paid-knives",
            "paid-pan",
            "paid-pin",
            "paid-scoop",
            "paid-thermo",
            "paid-trash",

            "yeme-beer",
            "yeme-becherovka",
            "yeme-mojito",
            "yeme-cognac",
            "yeme-redwine",
            "yeme-blue-hawaii",
            "yeme-whisky",
            "yeme-jagermeister",

            "flag-eu",
            "flag-usa",
            "flag-canada",
            "flag-uk",
            "flag-australia",
            "flag-new-zealand",
            "flag-france",
            "flag-germany",
            "flag-italy",
            "flag-spain",
            "flag-portugal",
            "flag-switzerland",
            "flag-netherlands",
            "flag-norway",
            "flag-sweden",
            "flag-finland",
            "flag-denmark",
            "flag-belgium",
            "flag-greece",
            "flag-japan",
            "flag-china",
            "flag-hongkong",
            "flag-korea",
            "flag-india",
            "flag-russia",
            "flag-ukraine",
            "flag-belarus",
            "flag-israel",
            "flag-turkey",
            "flag-brazil",
            "flag-jamaica",
            "flag-argentina",
            "flag-chile",
            "flag-colombia",
            "flag-vietnam",
            "flag-thailand",
            "flag-mexico",
            "flag-south-africa",
            "flag-indonesia",
            "flag-kenya",

            "paid3-smiley-happy",
            "paid3-smiley-unhappy",
            "paid3-bookmark",
            "paid3-ribbon",
            "paid3-apple",
            "paid3-anchor",
            "paid3-trafficlight",
            "paid3-goal",
            "paid3-userpic-hipster",
            "paid3-userpic-man",
            "paid3-userpic-lady",
            "paid3-userpic-steve",

            "paid4-christmas-reindeer",
            "paid4-christmas-sleigh",
            "paid4-christmas-gloves",
            "paid4-christmas-santa",
            "paid4-christmas-house",
            "paid4-christmas-tree",
            "paid4-christmas-cocktail",
            "paid4-christmas-glass",
            "paid4-christmas-giftbox",
            "paid4-christmas-giftbox2",
            "paid4-christmas-calendar",
            "paid4-christmas-candy",
            "paid4-christmas-gingerbread",
            "paid4-christmas-snowman",
            "paid4-christmas-sale",
            "paid4-christmas-globes",
        );
        $icons = array();
        foreach ($icon_ids as $icon_id) {
            $icons[$icon_id] = 'li-'.$icon_id.'@2x.png';
        }

        return $icons;
    }
}
