<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopAmount
 *
 * @method float getValue()
 * @property float $value
 */
class pocketlistsProPluginAutomationRuleShopAmount extends pocketlistsProPluginAutomationRuleShop
{
    const IDENTIFIER = 'order_amount';

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

        return pocketlistsProPluginComparision::compare($order->total, $this->value, $this->compare);
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

        return <<<HTML
<strong>{$this->getLabel()} {$this->compare} {$this->value} {$this->currency}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $input = waHtmlControl::getControl(
            waHtmlControl::INPUT,
            'data[rules]['.$this->getIdentifier().'][value]',
            ['value' => $this->value, 'class' => 'numerical short']
        );

        $currencies = [];
        foreach ((new shopCurrencyModel())->getAll('') as $item) {
            $currencies[] = [
                'title' => $item['code'],
                'value' => $item['code'],
            ];
        }
        $currencies = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][currency]',
            [
                'value'   => $this->currency,
                'options' => $currencies,
            ]
        );

        return <<<HTML
{$this->getSelectCompareControl($this->compare, pocketlistsProPluginComparision::getTypesGTfirst())}
{$this->getHiddenIdentifierControl()}
{$input}
{$currencies}
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtmlDisclaimer()
    {
        return '';
    }

    /**
     * @param array $json
     *
     * @return $this|pocketlistsProPluginSerializableInterface
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
