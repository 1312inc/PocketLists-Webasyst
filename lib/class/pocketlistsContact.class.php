<?php

/**
 * Class pocketlistsContact
 */
class pocketlistsContact
{
    /**
     * @var waContact
     */
    private $contact;

    /**
     * @var string
     */
    private $name = '(DELETED USER)';

    /**
     * @var string
     */
    private $username = '(DELETED USER)';

    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $photoUrl = '/wa-content/img/userpic96@2x.jpg';

    /**
     * @var string
     */
    private $userPic = '/wa-content/img/userpic20@2x.jpg';

    /**
     * @var bool
     */
    private $status = false;

    /**
     * @var string
     */
    private $teamrole = '';

    /**
     * @var string
     */
    private $login = 'deleted';

    /**
     * @var bool
     */
    private $me = false;

    /**
     * @var bool
     */
    private $exists = false;

    /**
     * @var int
     */
    private $lastActivity = 0;

    /**
     * @var array
     */
    private $itemsInfo = [
        'count'        => 0,
        'names'        => '',
        'max_priority' => 0,
    ];

    /**
     * pocketlistsContact constructor.
     *
     * @param waContact $contact
     */
    public function __construct(waContact $contact)
    {
        $this->contact = $contact;

        $this->init();
    }

    /**
     * @return $this
     */
    public function init()
    {
        if ($this->contact->exists()/* && $this->contact->get('is_user') != -1*/) {
            $this->me = ($this->contact->getId() == wa()->getUser()->getId());
            $this->name = $this->contact->getName();
            $this->username = $this->contact->getName();
            $this->id = $this->contact->getId();
            $this->photoUrl = $this->contact->getPhoto();
            $this->login = $this->contact->get('login');
            $this->userpic = $this->contact->getPhoto(20);
            $this->status = $this->contact->getStatus();
            $this->teamrole = $this->contact->get('jobtitle');
            $this->exists = true;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * @param int $lastActivity
     *
     * @return pocketlistsContact
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * @return array
     */
    public function getItemsInfo()
    {
        return $this->itemsInfo;
    }

    /**
     * @param array $itemsInfo
     *
     * @return pocketlistsContact
     */
    public function setItemsInfo($itemsInfo)
    {
        $this->itemsInfo = $itemsInfo;

        return $this;
    }

    /**
     * @return waContact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * @return string
     */
    public function getUserPic()
    {
        return $this->userPic;
    }

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTeamrole()
    {
        return $this->teamrole;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return bool
     */
    public function isMe()
    {
        return $this->me;
    }

    /**
     * @return bool
     */
    public function isExists()
    {
        return $this->exists;
    }
}
