<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopAmount
 */
class pocketlistsProPluginAutomationRuleShopAmount extends pocketlistsProPluginAutomationRuleAbstract
{
    const IDENTIFIER = 'order_amount';

    /**
     * @var float
     */
    protected $value;

    /**
     * @var string
     */
    protected $compare;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Amount');
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
        $input = waHtmlControl::getControl(
            waHtmlControl::INPUT,
            'automation[rule]['.$this->getIdentifier().'][value]',
            ['value' => $this->value, 'class' => 'numerical short']
        );

        $compareOptions = $this->getSelectCompare($this->compare);

        $currencies = [];
        foreach ((new shopCurrencyModel())->getAll() as $item) {
            $currencies[] = [
                'title' => $item['code'],
                'value' => $item['code'],
            ];
        }
        $currencies = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'automation[rule]['.$this->getIdentifier().'][currency]',
            [
                'value'   => $this->currency,
                'options' => $currencies,
            ]
        );

        return <<<HTML
{$compareOptions}
{$input}
{$currencies}
HTML;
    }

    /**
     * @param array $json
     *
     * @return pocketlistsProPluginAutomationRuleShopAction
     */
    public function load(array $json)
    {
        $this->value = (float)$json['value'];
        $this->currency = $json['currency'];
        $this->compare = $json['compare'];

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
            'currency'   => $this->currency,
            'compare'    => $this->compare,
        ];
    }
}