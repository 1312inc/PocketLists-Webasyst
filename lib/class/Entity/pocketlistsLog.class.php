<?php

/**
 * Class pocketlistsLog
 */
class pocketlistsLog extends pocketlistsEntity
{
    const ACTION_ADD        = 'add';
    const ACTION_EDIT       = 'edit';
    const ACTION_UPDATE     = 'update';
    const ACTION_DELETE     = 'delete';
    const ACTION_ASSIGN     = 'assign';
    const ACTION_ARCHIVE    = 'archive';
    const ACTION_UNARCHIVE  = 'unarchive';
    const ACTION_COMPLETE   = 'complete';
    const ACTION_UNCOMPLETE = 'uncomplete';

    const ENTITY_USER       = 'user';
    const ENTITY_POCKET     = 'pocket';
    const ENTITY_LIST       = 'list';
    const ENTITY_ITEM       = 'item';
    const ENTITY_COMMENT    = 'comment';
    const ENTITY_ATTACHMENT = 'attachment';

    const ITEM_ACTION_NEW_ASSIGN = 'new_assign';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $action;

    /**
     * @var int
     */
    private $pocket_id;

    /**
     * @var int
     */
    private $list_id;

    /**
     * @var int
     */
    private $item_id;

    /**
     * @var int
     */
    private $contact_id;

    /**
     * @var int
     */
    private $comment_id;

    /**
     * @var int
     */
    private $attachment_id;

    /**
     * @var int
     */
    private $location_id;

    /**
     * @var string
     */
    private $entity_type;

    /**
     * @var int|null
     */
    private $additional_id;

    /**
     * @var int|null
     */
    private $assigned_contact_id;

    /**
     * @var string
     */
    private $params;

    /**
     * @var \DateTime|string
     */
    private $create_datetime;

    /**
     * @var pocketlistsLogContext
     */
    private $context;

    /**
     * @var pocketlistsContact|null
     */
    private $_assignedContact;

    /**
     * @var pocketlistsContact
     */
    private $_contact;

    /**
     * @var array|null
     */
    private $paramsArray;

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
        if (empty($fields) || (!empty($fields) && array_key_exists('params', $fields))) {
            $this->params = json_encode($this->paramsArray, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @return array|null
     */
    public function getParamsArray()
    {
        if ($this->paramsArray === null) {
            $this->paramsArray = json_decode($this->params, true);
        }

        return $this->paramsArray;
    }

    /**
     * @param array|null $paramsArray
     *
     * @return pocketlistsLog
     */
    public function setParamsArray(array $paramsArray = null)
    {
        $this->paramsArray = $paramsArray;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     * @throws waException
     */
    public function afterHydrate($data = [])
    {
        if (!empty($data) && array_key_exists('params', $data)) {
            $this->setParamsArray(json_decode($data['params'], true));
        }
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
     * @return pocketlistsLog
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param int $action
     *
     * @return pocketlistsLog
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * @param int $entity_type
     *
     * @return pocketlistsLog
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAdditionalId()
    {
        return $this->additional_id;
    }

    /**
     * @param int|null $additional_id
     *
     * @return pocketlistsLog
     */
    public function setAdditionalId($additional_id)
    {
        $this->additional_id = $additional_id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string|null $params
     *
     * @return pocketlistsLog
     */
    public function setParams($params = null)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getCreateDatetime()
    {
        return $this->create_datetime;
    }

    /**
     * @param DateTime|string $create_datetime
     *
     * @return pocketlistsLog
     */
    public function setCreateDatetime($create_datetime)
    {
        $this->create_datetime = $create_datetime;

        return $this;
    }

    /**
     * @return int
     */
    public function getPocketId()
    {
        return $this->pocket_id;
    }

    /**
     * @param int $pocket_id
     *
     * @return pocketlistsLog
     */
    public function setPocketId($pocket_id)
    {
        $this->pocket_id = $pocket_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getListId()
    {
        return $this->list_id;
    }

    /**
     * @param int $list_id
     *
     * @return pocketlistsLog
     */
    public function setListId($list_id)
    {
        $this->list_id = $list_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return pocketlistsLog
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getContactId()
    {
        return $this->contact_id;
    }

    /**
     * @param int $contact_id
     *
     * @return pocketlistsLog
     */
    public function setContactId($contact_id)
    {
        $this->contact_id = $contact_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentId()
    {
        return $this->comment_id;
    }

    /**
     * @param int $comment_id
     *
     * @return pocketlistsLog
     */
    public function setCommentId($comment_id)
    {
        $this->comment_id = $comment_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getAttachmentId()
    {
        return $this->attachment_id;
    }

    /**
     * @param int $attachment_id
     *
     * @return pocketlistsLog
     */
    public function setAttachmentId($attachment_id)
    {
        $this->attachment_id = $attachment_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * @param int $location_id
     *
     * @return pocketlistsLog
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAssignedContactId()
    {
        return $this->assigned_contact_id;
    }

    /**
     * @param int|null $assigned_contact_id
     *
     * @return pocketlistsLog
     */
    public function setAssignedContactId($assigned_contact_id)
    {
        $this->assigned_contact_id = $assigned_contact_id;

        return $this;
    }

    /**
     * @return pocketlistsContact
     * @throws waException
     */
    public function getAssignContact()
    {
        if ($this->_assignedContact === null) {
            /** @var pocketlistsContactFactory $factory */
            $factory = pl2()->getEntityFactory(pocketlistsContact::class);
            $this->_assignedContact = $this->assigned_contact_id
                ? $factory->createNewWithId($this->assigned_contact_id)
                : $factory->createNewFake();
        }

        return $this->_assignedContact;
    }

    /**
     * @return pocketlistsContact
     * @throws waException
     */
    public function getContact()
    {
        if ($this->_contact === null) {
            $this->_contact = pl2()->getEntityFactory(pocketlistsContact::class)
                ->createNewWithId($this->contact_id);
        }

        return $this->_contact;
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return $this
     */
    public function fillWithContext(pocketlistsLogContext $context)
    {
        $this->context = $context;

        foreach ($this->context->getEntities() as $type => $entity) {
            if (method_exists($entity, 'getId')) {
                $this->{$type.'_id'} = $entity->getId();
            }
        }

        $params = $this->context->getParams();
        if (!empty($params)) {
            if (!is_array($this->getParamsArray())) {
                $this->paramsArray = [];
            }

            $this->setParamsArray(array_merge_recursive($this->getParamsArray(), $params));
        }

        $additional = $context->getAdditional();
        if ($additional) {
            if (is_object($additional) && method_exists($additional, 'getId')) {
                $this->additional_id = $additional->getId();
            } else {
                $this->additional_id = (int)$additional;
            }
        }

        $action = $context->getAction();
        if (!empty($action)) {
            $this->setAction($action);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getParamValueByKey($key, $default = null)
    {
        $keys = explode('.', $key);
        $val = $this->paramsArray;

        while ($keys) {
            $key = array_shift($keys);

            if (!isset($val[$key])) {
                return $default;
            }
            $val = $val[$key];
        }

        return $val;
    }
}
