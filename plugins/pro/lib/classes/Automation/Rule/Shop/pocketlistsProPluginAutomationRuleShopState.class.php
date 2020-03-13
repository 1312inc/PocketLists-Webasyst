<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopState
 */
class pocketlistsProPluginAutomationRuleShopState extends pocketlistsProPluginAutomationRuleShop
{
    const IDENTIFIER = 'order_state';

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $compare;

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Status');
    }

    /**
     * @return array
     */
    public function getPossibleValues()
    {
        return [];
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param shopOrder $order
     *
     * @return bool
     * @throws pocketlistsLogicException
     */
    public function match($order)
    {
        if ($this->isEmpty()) {
            return true;
        }

        // если есть флаг что это отложенная проверка
        // но пока не надо проверять ее - пропускаем
        if ($this->delayed && $this->skipDelayed) {
            return true;
        }

        return pocketlistsProPluginComparision::compare($order->state->getId(), $this->value, $this->compare);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function viewHtml()
    {
        if ($this->isEmpty()) {
            return '';
        }

        $workflowState = (new shopWorkflow())->getStateById($this->value);
        if (!$workflowState instanceof waWorkflowState) {
            return '';
        }

        $delayed = '';
        if ($this->delayed) {
            $delayed = _wp(' (delayed check)');
        }

        return <<<HTML
<strong>{$this->getLabel()} {$this->compare} {$workflowState->getName()}{$delayed}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $workflowStates = (new shopWorkflow())->getAllStates();
        $states = [[
            'title' => '',
            'value' => '',
        ]];
        foreach ($workflowStates as $workflowState) {
            $states[] = [
                'title' => _w($workflowState->getName()),
                'value' => $workflowState->getId(),
            ];
        }

        $input = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][value]',
            [
                'value'   => $this->value,
                'options' => $states,
            ]
        );

        return <<<HTML
{$this->getSelectCompareControl(
    $this->compare,
    [
        pocketlistsProPluginComparision::TYPE_EQ,
        pocketlistsProPluginComparision::TYPE_NEQ,
    ]
)}
{$this->getHiddenIdentifierControl()}
{$input}
{$this->getDelayedCheckboxControl()}
HTML;
    }

    /**
     * @param array $json
     *
     * @return $this|pocketlistsProPluginSerializableInterface
     */
    public function load(array $json)
    {
        $this->value = $json['value'];
        $this->compare = $json['compare'];
        $this->delayed = ifset($json, 'delayed', false);

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'identifier' => $this->getIdentifier(),
            'value'      => $this->value,
            'compare'    => $this->compare,
            'delayed'    => true
        ];
    }
}