<?php

/**
 * Class pocketlistsContact
 */
class pocketlistsContact
{
    /**
     * @var waContact
     */
    protected $contact;

    /**
     * @var string
     */
    protected $name = '(DELETED USER)';

    /**
     * @var string
     */
    protected $username = '(DELETED USER)';

    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $photoUrl = '/wa-content/img/userpic.svg';

    /**
     * @var string
     */
    protected $userPic = '/wa-content/img/userpic.svg';

    /**
     * @var string
     */
    protected $status = '';

    /**
     * @var string
     */
    protected $teamrole = '';

    /**
     * @var string
     */
    protected $login = 'deleted';

    /**
     * @var bool
     */
    protected $me = false;

    /**
     * @var bool
     */
    protected $exists = false;

    /**
     * @var int
     */
    protected $lastActivity = 0;

    /**
     * @var array
     */
    protected $listActivities;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var pocketlistsItemsCount|null
     */
    protected $itemsInfo;

    /**
     * @var string
     */
    protected $locale;

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
     * @return pocketlistsItemsCount|null
     */
    public function getItemsInfo()
    {
        return $this->itemsInfo;
    }

    /**
     * @param pocketlistsItemsCount $itemsInfo
     *
     * @return pocketlistsContact
     */
    public function setItemsInfo(pocketlistsItemsCount $itemsInfo)
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

    /**
     * @return string|null
     */
    public function getLocale()
    {
        if ($this->locale === null) {
            static $contact_model;
            if (!$contact_model instanceof waContactModel) {
                $contact_model = new waContactModel();
            }

            $contact_info = $contact_model->getById($this->id);
            $this->locale = isset($contact_info['locale']) ? $contact_info['locale'] : null;
        }

        return $this->locale;
    }
}
