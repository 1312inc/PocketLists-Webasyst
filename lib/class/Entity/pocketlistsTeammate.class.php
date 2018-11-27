<?php

/**
 * Class pocketlistsTeammate
 */
class pocketlistsTeammate extends pocketlistsEntity
{
    /**
     * @var waContact
     */
    protected $contact;

    protected $name = '(DELETED USER)';
    protected $username = '(DELETED USER)';
    protected $id = 0;
    protected $photo_url = '/wa-content/img/userpic96@2x.jpg';
    protected $userpic = '/wa-content/img/userpic20@2x.jpg';
    protected $status = false;
    protected $teamrole = '';
    protected $login = 'deleted';
    protected $me = false;
    protected $last_activity = 0;
    protected $items_info = [
        'count'        => 0,
        'names'        => "",
        'max_priority' => 0,
    ];

    /**
     * pocketlistsTeammate constructor.
     *
     * @param waContact $model
     */
    public function __construct(waContact $model)
    {
        $this->setContact($model);
    }

    /**
     * @return waContact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param waContact $contact
     *
     * @return pocketlistsTeammate
     */
    public function setContact(waContact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return pocketlistsTeammate
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return pocketlistsTeammate
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return pocketlistsTeammate
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photo_url;
    }

    /**
     * @param string $photo_url
     *
     * @return pocketlistsTeammate
     */
    public function setPhotoUrl($photo_url)
    {
        $this->photo_url = $photo_url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserpic()
    {
        return $this->userpic;
    }

    /**
     * @param string $userpic
     *
     * @return pocketlistsTeammate
     */
    public function setUserpic($userpic)
    {
        $this->userpic = $userpic;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     *
     * @return pocketlistsTeammate
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getTeamrole()
    {
        return $this->teamrole;
    }

    /**
     * @param string $teamrole
     *
     * @return pocketlistsTeammate
     */
    public function setTeamrole($teamrole)
    {
        $this->teamrole = $teamrole;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     *
     * @return pocketlistsTeammate
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMe()
    {
        return $this->me;
    }

    /**
     * @param bool $me
     *
     * @return pocketlistsTeammate
     */
    public function setMe($me)
    {
        $this->me = $me;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastActivity()
    {
        return $this->last_activity;
    }

    /**
     * @param int $last_activity
     *
     * @return pocketlistsTeammate
     */
    public function setLastActivity($last_activity)
    {
        $this->last_activity = $last_activity;

        return $this;
    }

    /**
     * @return array
     */
    public function getItemsInfo()
    {
        return $this->items_info;
    }

    /**
     * @param array $items_info
     *
     * @return pocketlistsTeammate
     */
    public function setItemsInfo($items_info)
    {
        $this->items_info = $items_info;

        return $this;
    }

    /**
     * @param array $data
     */
    public function fillData(array $data)
    {
        foreach ($data as $key => $datum) {
            if (property_exists($this, $key)) {
                $this->$key = $datum;
            }
        }
    }
}