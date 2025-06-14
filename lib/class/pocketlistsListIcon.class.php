<?php

/**
 * Class pocketlistsListIcon
 */
class pocketlistsListIcon
{
    /**
     * @return array
     */
    public static function getAll()
    {
        $icon_ids = [
            '' => [
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
            ],
            _w('Work & Office') => [
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
            ],
            _w('Shopping') => [
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
            ],
            _w('Home & Family') => [
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
            ],
            _w('Travel & Vacations') => [
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
            ],
            _w('Nature & Outdoors') => [
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
            ],
            _w('Holidays & Events') => [
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
            ],
            _w('School & Education') => [
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
            ],
            _w('Hobbies & Games') => [
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
            ],
            _w('Food & Drink') => [
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
            ],
            _w('Health & Medical') => [
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
            ],
            _w('Sports & Activities') => [
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
            ],
            _w('Toys & Babies') => [
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
            ],
            _w('Pets & Animals') => [
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
            ],
            _w('Tech & Gadgets') => [
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
            ],
            _w('Signs & Symbols') => [
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
            ],
            _w('Flags') => [
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
                "flag-pirate",
            ],
        ];
        $icon_ids_ret = [];
        foreach ($icon_ids as $group =>  $icons) {
            $icon_ids_ret[$group] = [];
            foreach ($icons as $id => $icon_id) {
                $icon_ids_ret[$group][$icon_id] = 'li-'.$icon_id.'@2x.png';
            }
        }

        return $icon_ids_ret;
    }
}
