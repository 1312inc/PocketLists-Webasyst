<?php

/**
 * Class pocketlistsProPluginAutomation
 */
class pocketlistsProPluginAutomation extends pocketlistsEntity implements kmAutomationInterface
{
    const GROUP_SHOP = 'shop';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    protected $event;

    /**
     * @var array|string
     */
    protected $rules;

    /**
     * @var array|string
     */
    protected $action;

    /**
     * @var string
     */
    protected $group;

    /**
     * @var int
     */
    protected $created_by;

    /**
     * @var string|DateTime
     */
    protected $created_datetime;

    /**
     * @var string|DateTime
     */
    protected $updated_datetime;

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
     * @return pocketlistsProPluginAutomation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array|string $rules
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param array|string $action
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param int $created_by
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;

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
     * @return pocketlistsProPluginAutomation
     */
    public function setCreatedDatetime($created_datetime)
    {
        $this->created_datetime = $created_datetime;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getUpdatedDatetime()
    {
        return $this->updated_datetime;
    }

    /**
     * @param DateTime|string $updated_datetime
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setUpdatedDatetime($updated_datetime)
    {
        $this->updated_datetime = $updated_datetime;

        return $this;
    }

    public function afterHydrate($data = [])
    {
        $this->action = json_decode($this->action, true);
        $this->rules = json_decode($this->rules, true);
    }

    public function afterExtract(array &$fields)
    {
        if (array_key_exists('action', $fields))  {
            $fields['action'] = json_encode($fields['action'], JSON_UNESCAPED_UNICODE);
        }

        if (array_key_exists('rules', $fields))  {
            $fields['rules'] = json_encode($fields['rules'], JSON_UNESCAPED_UNICODE);
        }

        $fields['updated_datetime'] = date('Y-m-d H:i:s');

        if (empty($fields['id'])) {
            $fields['created_datetime'] = date('Y-m-d H:i:s');
            $fields['created_by'] = wa()->getUser()->getId();
        }
    }

    /**
     * @return bool
     * @throws pocketlistsLogicException
     */
    public function checkRules()
    {
        foreach ($this->rules as $ruleData) {
            $rule = pocketlistsProPluginAutomationRuleFactory::create($ruleData, $this->group);

            if (!$rule->match()) {
                return false;
            }
        }

        return true;
    }

    public function performAction()
    {
        // TODO: Implement performAction() method.
    }
}