<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopState
 *
 * @method array getValue()
 * @property array $value
 */
class pocketlistsProPluginAutomationRuleShopState extends pocketlistsProPluginAutomationRuleShop
{
    const IDENTIFIER = 'order_state';

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
     * @param shopOrder $order
     *
     * @return bool
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

        switch ($this->compare) {
            case pocketlistsProPluginComparision::TYPE_EQ:
                return in_array($order->state->getId(), $this->value);

            case pocketlistsProPluginComparision::TYPE_NEQ:
                return !in_array($order->state->getId(), $this->value);

            default:
                return false;
        }
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty(array_filter($this->value));
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

        $workflow = new shopWorkflow();
        $states = [];
        foreach ($this->value as $item) {
            $workflowState = $workflow->getStateById($item);
            if (!$workflowState instanceof waWorkflowState) {
                continue;
            }

            $states[] = $workflowState->getName();
        }
        $states = implode(', ', $states);

        $delayed = '';
        if ($this->delayed) {
            $delayed = _wp(' (delayed check)');
        }

        return <<<HTML
<strong>{$this->getLabel()} {$this->compare} {$states}{$delayed}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $workflowStates = (new shopWorkflow())->getAllStates();
        $states = [['title' => _wp('any'), 'value' => '']];
        foreach ($workflowStates as $workflowState) {
            $states[] = [
                'title' => _w($workflowState->getName()),
                'value' => $workflowState->getId(),
            ];
        }

        return <<<HTML
{$this->getSelectCompareControl(
    $this->compare,
    [
        pocketlistsProPluginComparision::TYPE_EQ,
        pocketlistsProPluginComparision::TYPE_NEQ,
    ]
)}
{$this->getHiddenIdentifierControl()}
{$this->getMultipleSelectControl($states)}
{$this->getDelayedCheckboxControl()}
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtmlDisclaimer()
    {
        return _wp('For Windows: Hold down the control (ctrl) button to select multiple options.<br> For Mac: Hold down the command button to select multiple options.');
    }

    /**
     * @param array $json
     *
     * @return $this|pocketlistsProPluginSerializableInterface
     */
    public function load(array $json)
    {
        if (!empty($json['value']) && !is_array($json['value'])) {
            $this->value = [$json['value']];
        } elseif (empty($json['value'])) {
            $this->value = [];
        } else {
            $this->value = $json['value'];
        }
        $this->compare = $json['compare'];
        $this->delayed = (bool)ifset($json, 'delayed', false);

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
            'delayed'    => (bool)$this->delayed
        ];
    }
}
