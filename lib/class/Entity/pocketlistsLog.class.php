<?php

/**
 * Class pocketlistsLog
 */
class pocketlistsLog extends pocketlistsEntity
{
    const ACTION_ADD            = 'add';
    const ACTION_EDIT           = 'edit';
    const ACTION_UPDATE         = 'update';
    const ACTION_DELETE         = 'delete';
    const ACTION_ATTACHMENT_ADD = 'attach';
    const ACTION_ASSIGN         = 'assign';

    const ENTITY_USER   = 'user';
    const ENTITY_POCKET = 'pocket';
    const ENTITY_LIST   = 'list';
    const ENTITY_ITEM   = 'item';

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
    private $entity_id;

    /**
     * @var string
     */
    private $entity_type;

    /**
     * @var int|null
     */
    private $additional_id;

    /**
     * @var array|null
     */
    private $data;

    /**
     * @var array|null
     */
    private $params;

    /**
     * @var \DateTime|string
     */
    private $created_datetime;

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
        if (array_key_exists('data', $fields)) {
            $this->data = waUtils::jsonEncode($this->data);
        }
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     * @throws waException
     */
    public function afterHydrate($data = [])
    {
        if (array_key_exists('data', $data)) {
            $this->data = waUtils::jsonDecode($data['data']);
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
     * @return int
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
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @param int $entity_id
     *
     * @return pocketlistsLog
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;

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
     * @return array|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array|null $data
     *
     * @return pocketlistsLog
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array|null $params
     *
     * @return pocketlistsLog
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getCreatedDatetime()
    {
        return $this->created_datetime;
    }

    /**
     * @param DateTime|string $created_datetime
     *
     * @return pocketlistsLog
     */
    public function setCreatedDatetime($created_datetime)
    {
        $this->created_datetime = $created_datetime;

        return $this;
    }
}
