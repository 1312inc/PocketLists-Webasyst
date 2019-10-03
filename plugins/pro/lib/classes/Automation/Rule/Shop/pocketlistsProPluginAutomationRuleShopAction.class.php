<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopAction
 */
class pocketlistsProPluginAutomationRuleShopAction extends pocketlistsProPluginAutomationRuleAbstract
{
    const IDENTIFIER = 'action';

    /**
     * @var shopWorkflowAction
     */
    private $value;

    /**
     * @var array
     */
    private $options;

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Action');
    }

    /**
     * @return array
     */
    public function getPossibleValues()
    {
        return [];
    }

    /**
     * @return shopWorkflowAction|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $data
     *
     * @return bool
     * @throws pocketlistsLogicException
     */
    public function match($data)
    {
        return pocketlistsProPluginComparision::compare($data, $this->value->getId(), $this->compare);
    }

    /**
     * @return string
     */
    public function viewHtml()
    {
        return $this->editHtml();
    }

    /**
     * @return string
     */
    public function editHtml()
    {
        $options = print_r($this->options, 1);

        $input = waHtmlControl::getControl(
            waHtmlControl::HIDDEN,
            'automation[rule]['.$this->getIdentifier().'][value]',
            ['value' => $this->value->getId()]
        );

        return <<<HTML
html forms here
 <span class="pl2pro-ss-order-action">
    <i class="icon16 color" style="background-color: rebeccapurple;"></i>
    {$this->value->getName()}
    <br>{$options}
    {$input}
</span>
HTML;
    }

    /**
     * @param array $json
     *
     * @return pocketlistsProPluginAutomationRuleShopAction
     */
    public function load(array $json)
    {
        $this->value = (new shopWorkflow())->getActionById($json['value']);
        $this->options = $this->value->getOptions();

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
            'value'      => $this->value->getId(),
        ];
    }
}