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
     * @var string
     */
    private $status = '';

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
    private $listActivities;

    /**
     * @var string|null
     */
    private $email;

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
        if ($this->contact->exists()) {
            $this->me = ($this->contact->getId() == wa()->getUser()->getId());
            $this->name = $this->contact->getName();
            $this->username = $this->contact->getName();
            $this->id = $this->contact->getId();
            $this->photoUrl = $this->contact->getPhoto();
            $this->login = $this->contact->get('login');
            $this->userPic = $this->contact->getPhoto(20);
            $this->status = $this->contact->getStatus();
            $this->teamrole = $this->contact->get('jobtitle');
            $this->exists = $this->contact->get('is_user') != -1;
            $this->email = $this->getContact()->get('email', 'default');
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return pocketlistsContact
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * @param string $key
     *
     * @return array|mixed
     */
    public function getItemsInfo($key = '')
    {
        return isset($this->itemsInfo[$key]) ? $this->itemsInfo[$key] : $this->itemsInfo;
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
     * @return string
     */
    public function getStatus()
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

    /**
     * @return array|int
     * @throws waException
     */
    public function getListActivities(pocketlistsList $list = null)
    {
        if ($this->listActivities === null) {
            /** @var pocketlistsListModel $listModel */
            $listModel = pl2()->getModel(pocketlistsList::class);
            $this->listActivities = $listModel->getLastActivitiesList($this->getId());
        }

        if ($list)  {
            return isset($this->listActivities[$list->getId()]) ? $this->listActivities[$list->getId()] : 0;
        }

        return $this->listActivities;
    }
}
