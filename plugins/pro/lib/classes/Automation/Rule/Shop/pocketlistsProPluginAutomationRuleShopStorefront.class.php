<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopStorefront
 */
class pocketlistsProPluginAutomationRuleShopStorefront extends pocketlistsProPluginAutomationRuleAbstract
{
    const IDENTIFIER = 'storefront';

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
        return _wp('Storefront');
    }

    /**
     * @return array
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            wa('site');
            $this->possibleValues = (new siteDomainModel())->getAll();
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
        if (empty($this->value)) {
            return true;
        }

        $storefront = isset($order->params['storefront']) ? $order->params['storefront'] : null;

        return $storefront == $this->value;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function viewHtml()
    {
        return $this->editHtml();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $domains = [['title' => '', 'value' => '']];
        foreach ($this->getPossibleValues() as $item) {
            $domains[] = [
                'title' => $item['name'],
                'value' => $item['name'],
            ];
        }

        $domains = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][value]',
            [
                'value'   => $this->value,
                'options' => $domains,
            ]
        );

        return <<<HTML
{$this->getHiddenIdentifierControl()}
{$domains}
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