<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopStorefront
 *
 * @method array getValue()
 * @property array $value
 */
class pocketlistsProPluginAutomationRuleShopStorefront extends pocketlistsProPluginAutomationRuleShop
{
    const IDENTIFIER = 'storefront';

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
     * @param shopOrder $order
     *
     * @return bool
     */
    public function match($order)
    {
        if ($this->isEmpty()) {
            return true;
        }

        $storefront = isset($order->params['storefront']) ? $order->params['storefront'] : '';

        return in_array($storefront, $this->value);
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

        $values = implode(', ', $this->value);

        return <<<HTML
<strong>{$this->getLabel()} {$values}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $domains = [['title' => _wp('any'), 'value' => '']];
        foreach ($this->getPossibleValues() as $item) {
            $domains[] = [
                'title' => $item['name'],
                'value' => $item['name'],
            ];
        }

        return <<<HTML
{$this->getHiddenIdentifierControl()}
{$this->getMultipleSelectControl($domains)}
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
        $this->value = $this->loadValueAsArray($json);

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
