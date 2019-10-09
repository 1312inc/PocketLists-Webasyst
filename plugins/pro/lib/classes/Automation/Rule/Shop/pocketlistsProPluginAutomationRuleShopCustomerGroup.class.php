<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopCustomerGroup
 */
class pocketlistsProPluginAutomationRuleShopCustomerGroup extends pocketlistsProPluginAutomationRuleAbstract
{
    const IDENTIFIER = 'customer_group';

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $possibleValues;

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Customer group');
    }

    /**
     * @return array
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            $this->possibleValues = (new waGroupModel())->getNames();
        }

        return $this->possibleValues;
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
     */
    public function match($order)
    {
        if ($this->isEmpty()) {
            return true;
        }

        return in_array($this->value, (new waUserGroupsModel())->getGroupIds($order->customer_id));
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

        $group = (new waGroupModel())->getName($this->value);

        return <<<HTML
<strong>{$this->getLabel()} = {$group}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $groups = [['title' => '', 'value' => '']];
        foreach ($this->getPossibleValues() as $id => $item) {
            $groups[] = [
                'title' => $item,
                'value' => $id,
            ];
        }

        $groups = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][value]',
            [
                'value'   => $this->value,
                'options' => $groups,
            ]
        );

        return <<<HTML
{$this->getHiddenIdentifierControl()}
{$groups}
HTML;
    }

    /**
     * @param array $json
     *
     * @return pocketlistsProPluginAutomationRuleShopAction
     */
    public function load(array $json)
    {
        $this->value = $json['value'];

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
        ];
    }
}