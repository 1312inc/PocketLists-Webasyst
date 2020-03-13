<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopAction
 */
class pocketlistsProPluginAutomationRuleShopAction extends pocketlistsProPluginAutomationRuleShop
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
     * @param shopWorkflowAction $action
     *
     * @return bool
     */
    public function match($action)
    {
        return $action->getId() === $this->value->getId();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function viewHtml()
    {
        return '';
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $input = waHtmlControl::getControl(
            waHtmlControl::HIDDEN,
            'data[rules]['.$this->getIdentifier().'][value]',
            ['value' => $this->value->getId()]
        );

        $style = $this->value->getOption('icon', 'color');
        $bgColor = $this->value->getOption('button_class', 'gray');

        return <<<HTML
 <span class="pl2pro-ss-order-action">
    <i class="icon16 {$style}" style="background-color: {$bgColor};"></i>
    {$this->value->getName()}
    <br>
    {$input}
    {$this->getHiddenIdentifierControl()}
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