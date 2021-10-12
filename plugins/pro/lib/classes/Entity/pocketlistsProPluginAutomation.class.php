<?php

/**
 * Class pocketlistsProPluginAutomation
 */
class pocketlistsProPluginAutomation extends pocketlistsEntity
{
    const TYPE_SHOP = 'shop';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $event;

    /**
     * @var pocketlistsProPluginAutomationRuleInterface[]|string
     */
    private $rules;

    /**
     * @var pocketlistsProPluginAutomationActionInterface|string
     */
    private $action;

    /**
     * @var string
     */
    private $type = self::TYPE_SHOP;

    /**
     * @var int
     */
    private $created_by;

    /**
     * @var DateTime|string
     */
    private $created_datetime;

    /**
     * @var DateTime|string
     */
    private $updated_datetime;

    /**
     * @var string
     */
    private $rulesJson;

    /**
     * @var
     */
    private $actionJson;

    /**
     * @var int
     */
    private $execution_count = 0;

    /**
     * @var DateTime|string
     */
    private $last_execution_datetime;

    /**
     * @var bool
     */
    private $enabled = 1;

    /**
     * @var bool
     */
    private $isValid = true;

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
     * @return pocketlistsProPluginAutomationRuleInterface[]|string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param pocketlistsProPluginAutomationRuleInterface[]|string $rules
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return pocketlistsProPluginAutomationActionInterface|string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param pocketlistsProPluginAutomationActionInterface|string $action
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setType($type)
    {
        $this->type = $type;

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

    /**
     * @return int
     */
    public function getExecutionCount()
    {
        return $this->execution_count;
    }

    /**
     * @param int $executionCount
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setExecutionCount($executionCount)
    {
        $this->execution_count = $executionCount;

        return $this;
    }

    /**
     * @param int $count
     *
     * @return pocketlistsProPluginAutomation
     */
    public function incExecutionCount($count = 1)
    {
        $this->execution_count += $count;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getLastExecutionDatetime()
    {
        return $this->last_execution_datetime;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @param DateTime|string $last_execution_datetime
     *
     * @return pocketlistsProPluginAutomation
     */
    public function setLastExecutionDatetime($last_execution_datetime)
    {
        $this->last_execution_datetime = $last_execution_datetime;

        return $this;
    }

    public function afterExtract(array &$fields)
    {
        $rules = $this->rules;
        $this->rules = $this->rulesJson;
        $this->rulesJson = $rules;

        $action = $this->action;
        $this->action = $this->actionJson;
        $this->actionJson = $action;
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     * @throws waException
     */
    public function afterHydrate($data = [])
    {
        if ($this->rules) {
            $rules = [];

            if (!is_array($this->rules)) {
                $this->rules = json_decode($this->rules, true);
            }

            foreach ($this->rules as $rule) {
                if (!empty($rule['identifier'])) {
                    try {
                        $rules[] = pocketlistsProPlugin::getInstance()->getAutomationService()->createRule(
                            $rule['identifier'],
                            $rule
                        );
                    } catch (pocketlistsProPluginNoShopActionException $exception) {
                    } catch (Exception $exception) {
                        pocketlistsLogger::error($exception->getMessage());
                        $this->isValid = false;

                        return;
                    }
                }
            }
            $this->rules = $rules;
            $this->rulesJson = json_encode($this->rules, JSON_UNESCAPED_UNICODE);
        }

        if ($this->action) {
            if (!is_array($this->action)) {
                $this->action = json_decode($this->action, true);
            }

            $this->action = (new pocketlistsProPluginCreateItemAction())->load($this->action);
            $this->actionJson = json_encode($this->action, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
        $rules = $this->rules;
        $this->rules = $this->rulesJson;
        $this->rulesJson = $rules;

        $action = $this->action;
        $this->action = $this->actionJson;
        $this->actionJson = $action;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }
}
