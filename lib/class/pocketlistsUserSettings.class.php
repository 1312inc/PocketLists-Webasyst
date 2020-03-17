<?php

/**
 * Class pocketlistsUserSettings
 */
class pocketlistsUserSettings
{
    private $settings;
    private $contact_id;

    /**
     * @var waContactSettingsModel
     */
    private $csm;

    /**
     * @var string
     */
    private $app_id = 'pocketlists';

    const ICON_OVERDUE                    = 1;
    const ICON_OVERDUE_TODAY              = 2;
    const ICON_OVERDUE_TODAY_AND_TOMORROW = 3;
    const ICON_ALL                        = 99;
    const ICON_NONE                       = 0;

    const DAILY_RECAP_FOR_TODAY              = 0;
    const DAILY_RECAP_FOR_TODAY_AND_TOMORROW = 1;
    const DAILY_RECAP_FOR_NEXT_7_DAYS        = 2;

    const EMAIL_WHEN_SOMEONE_COMPLETES_ITEM_I_CREATED       = 0;
    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE       = 1;
    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST = 2;
    const EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM              = 3;

    const EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST = 0;
    const EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST      = 1;

    const EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM          = 0;
    const EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM = 1;
    const EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM    = 2;

    const MY_TO_DOS_CREATED_BY_ME_IN_SHARED_ANY_LIST       = 0;
    const MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS = 1;

    const MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS             = 0;
    const MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_GREEN_YELLOW_RED_ALL_LISTS = 1;

    /**
     * pocketlistsUserSettings constructor.
     *
     * @param bool|int $contact_id
     */
    public function __construct($contact_id = false)
    {
        $this->csm = new waContactSettingsModel();
        $this->setContact($contact_id);
    }

    /**
     * @param bool|int $contact_id
     */
    public function setContact($contact_id = false)
    {
        $this->contact_id = $contact_id ?: wa()->getUser()->getId();
        $this->settings = $this->csm->get($this->contact_id, $this->app_id);
    }

    public function saveDefaults()
    {
        foreach ($this->getDefaults() as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return [
            'app_icon'                       => self::ICON_ALL,
            'daily_recap_on'                 => 1,
            'daily_recap'                    => self::DAILY_RECAP_FOR_TODAY,
            'email_assign_me'                => 1,
            'email_complete_item_on'         => 1,
            'email_complete_item'            => self::EMAIL_WHEN_SOMEONE_COMPLETES_ITEM_I_CREATED,
            'email_add_item_on'              => 1,
            'email_add_item'                 => self::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST,
            'email_comment_item_on'          => 1,
            'email_comment_item'             => self::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM,
            'email_create_list_on'           => 1,
            'natural_input_on'               => 1,
            'created_by_others_in_shared_on' => 0,
            'created_by_others_in_shared'    => self::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_GREEN_YELLOW_RED_ALL_LISTS,
            'created_by_me_in_shared_on'     => 1,
            'created_by_me_in_shared'        => self::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_ANY_LIST,
        ];
    }

    /**
     * returns array of zero settings before save new
     *
     * @return array
     */
    public function getZeroSettings()
    {
        return [
            'daily_recap_on'                 => 0,
            'email_assign_me'                => 0,
            'email_complete_item_on'         => 0,
            'email_add_item_on'              => 0,
            'email_comment_item_on'          => 0,
            'email_create_list_on'           => 0,
            'created_by_others_in_shared_on' => 0,
            'created_by_me_in_shared_on'     => 0,
        ];
    }

    /**
     * @param $name
     * @param $value
     *
     * @return bool|resource
     */
    public function set($name, $value)
    {
        return $this->csm->set($this->contact_id, $this->app_id, $name, $value);
    }

    /**
     * @return array
     */
    public function getAllSettings()
    {
        return $this->settings;
    }

    /**
     * @return bool
     */
    public function appIcon()
    {
        return isset($this->settings['app_icon']) ? $this->settings['app_icon'] : false;
    }

    /**
     * @param $icon
     *
     * @return array
     */
    public function getIconPrioririesMapping($icon)
    {
        $mapping = [
            self::ICON_OVERDUE => [
                pocketlistsItem::PRIORITY_RED,
                pocketlistsItem::PRIORITY_BLACK,
                pocketlistsItem::PRIORITY_BURNINHELL,
            ],

            self::ICON_OVERDUE_TODAY => [
                pocketlistsItem::PRIORITY_YELLOW,
                pocketlistsItem::PRIORITY_RED,
                pocketlistsItem::PRIORITY_BLACK,
                pocketlistsItem::PRIORITY_BURNINHELL,
            ],

            self::ICON_OVERDUE_TODAY_AND_TOMORROW => [
                pocketlistsItem::PRIORITY_GREEN,
                pocketlistsItem::PRIORITY_YELLOW,
                pocketlistsItem::PRIORITY_RED,
                pocketlistsItem::PRIORITY_BLACK,
                pocketlistsItem::PRIORITY_BURNINHELL,
            ],

            self::ICON_ALL => [
                pocketlistsItem::PRIORITY_NORM,
                pocketlistsItem::PRIORITY_GREEN,
                pocketlistsItem::PRIORITY_YELLOW,
                pocketlistsItem::PRIORITY_RED,
                pocketlistsItem::PRIORITY_BLACK,
                pocketlistsItem::PRIORITY_BURNINHELL,
            ],

            self::ICON_NONE => [-1312],
        ];

        return isset($mapping[$icon]) ? $mapping[$icon] : $mapping[self::ICON_NONE];
    }

    /**
     * @return bool
     */
    public function emailDailyRecap()
    {
        return !empty($this->settings['daily_recap_on']) ? $this->settings['daily_recap'] : false;
    }

    /**
     * @return bool
     */
    public function emailWhenNewAssignToMe()
    {
        return !empty($this->settings['email_assign_me']) ? true : false;
    }

    /**
     * @return bool
     */
    public function emailWhenItemMarkedAsCompleted()
    {
        return !empty($this->settings['email_complete_item_on']) ? $this->settings['email_complete_item'] : false;
    }

    /**
     * @return bool
     */
    public function emailWhenAddsItem()
    {
        return !empty($this->settings['email_add_item_on']) ? $this->settings['email_add_item'] : false;
    }

    /**
     * @return bool
     */
    public function emailWhenAddsComment()
    {
        return !empty($this->settings['email_comment_item_on']) ? $this->settings['email_comment_item'] : false;
    }

    /**
     * @return bool
     */
    public function isCreateNewList()
    {
        return !empty($this->settings['email_create_list_on']) ? true : false;
    }

    /**
     * @return bool|int
     */
    public function getLastPocketList()
    {
        return !empty($this->settings['last_pocket_list_id']) ? json_decode(
            $this->settings['last_pocket_list_id'],
            true
        ) : false;
    }

    /**
     * @return bool
     */
    public function getNaturalInput()
    {
        return !empty($this->settings['natural_input_on']) ? $this->settings['natural_input_on'] : false;
    }

    /**
     * @return bool
     */
    public function myToDosCreatedByOthers()
    {
        return !empty($this->settings['created_by_others_in_shared_on']) ? $this->settings['created_by_others_in_shared'] : -1;
    }

    /**
     * @return bool
     */
    public function myToDosCreatedByMe()
    {
        return !empty($this->settings['created_by_me_in_shared_on']) ? $this->settings['created_by_me_in_shared'] : -1;
    }
}
