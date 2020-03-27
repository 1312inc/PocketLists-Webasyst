<?php

/**
 * Class pocketlistsProPluginDelayedAutomation
 */
class pocketlistsProPluginDelayedAutomation extends pocketlistsEntity
{
    const STATUS_NEW = 0;
    const STATUS_PROCESS = 1;
    const STATUS_OK = 2;
    const STATUS_ERROR = 3;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $status = self::STATUS_NEW;

    /**
     * @var int
     */
    private $automation_id;

    /**
     * @var pocketlistsProPluginAutomation
     */
    private $automation;

    /**
     * @var string|array
     */
    private $event_data;

    /**
     * @var DateTime|string
     */
    private $apply_datetime;

    /**
     * @var DateTime|string
     */
    private $created_datetime;

    /**
     * @var DateTime|string
     */
    private $executed_datetime;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $eventDataJson;

    /**
     * @var int|null
     */
    private $item_id;

    public function afterExtract(array &$fields)
    {
        $eventData = $this->event_data;
        $this->event_data = $this->eventDataJson;
        $this->eventDataJson = $eventData;
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     * @throws waException
     */
    public function afterHydrate($data = [])
    {
        if ($this->event_data) {
            if (!is_array($this->event_data)) {
                $this->event_data = json_decode($this->event_data, true);
            }
//            $this->action_result = $rules;
            $this->eventDataJson = json_encode($this->event_data, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
        $eventData = $this->event_data;
        $this->event_data = $this->eventDataJson;
        $this->eventDataJson = $eventData;
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
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getAutomationId()
    {
        return $this->automation_id;
    }

    /**
     * @param int $automation_id
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setAutomationId($automation_id)
    {
        $this->automation_id = $automation_id;

        return $this;
    }

    /**
     * @return pocketlistsProPluginAutomation
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function getAutomation()
    {
        if ($this->automation === null) {
            $this->automation = pl2()->getEntityFactory(pocketlistsProPluginAutomation::class)->findById($this->automation_id);

            if (!$this->automation instanceof pocketlistsProPluginAutomation) {
                throw new pocketlistsNotFoundException(sprintf('Not found automation %d for this delayed %d', $this->automation_id, $this->id));
            }
        }

        return $this->automation;
    }

    /**
     * @param pocketlistsProPluginAutomation $automation
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setAutomation(pocketlistsProPluginAutomation $automation)
    {
        $this->automation = $automation;
        $this->automation_id = $automation->getId();

        return $this;
    }

    /**
     * @return array|string
     */
    public function getEventData()
    {
        return $this->event_data;
    }

    /**
     * @param array|string $event_data
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setEventData($event_data)
    {
        $this->event_data = $event_data;
        $this->eventDataJson = json_encode($this->event_data, JSON_UNESCAPED_UNICODE);

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getApplyDatetime()
    {
        return $this->apply_datetime;
    }

    /**
     * @param DateTime|string $apply_datetime
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setApplyDatetime($apply_datetime)
    {
        $this->apply_datetime = $apply_datetime;

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
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setCreatedDatetime($created_datetime)
    {
        $this->created_datetime = $created_datetime;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getExecutedDatetime()
    {
        return $this->executed_datetime;
    }

    /**
     * @param DateTime|string $executed_datetime
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setExecutedDatetime($executed_datetime)
    {
        $this->executed_datetime = $executed_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    public function setCreatedBy($getId)
    {
    }

    /**
     * @return int|null
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int|null $item_id
     *
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }
}
