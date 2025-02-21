<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopPayment
 *
 * @method string getValue()
 * @property string $value
 */
class pocketlistsProPluginAutomationRuleShopPayment extends pocketlistsProPluginAutomationRuleShop
{
    const IDENTIFIER = 'payment';

    /**
     * @var shopPluginModel
     */
    protected static $shopPluginModel;

    /**
     * @var array
     */
    protected $possibleValues;

    /**
     * @var string
     */
    protected $compare = pocketlistsProPluginComparision::TYPE_EQ;

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Payment');
    }

    /**
     * @return array
     * @throws waException
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            $this->possibleValues = [];
            $instances = $this->getShopPluginModel()->listPlugins(shopPluginModel::TYPE_PAYMENT, ['all' => true,]);

            foreach ($instances as $instance) {
                $this->possibleValues[$instance['id']] = $instance['name'];
            }
        }

        return $this->possibleValues;
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

        return pocketlistsProPluginComparision::compare($order->params['payment_id'], $this->value, $this->compare);
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

        $instances = $this->getShopPluginModel()->listPlugins(shopPluginModel::TYPE_PAYMENT, array('all' => true,));
        $name = ifset($instances, $this->value, 'name', sprintf_wp('No payment option with the ID = %s is defined. Please check if settings need to be updated!', $this->value));

        return <<<HTML
<strong>{$this->getLabel()} {$this->compare} {$name}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $options = [['title' => _wp('any'), 'value' => '']];
        foreach ($this->getPossibleValues() as $id => $possibleValue) {
            $options[] = [
                'title' => $possibleValue,
                'value' => $id,
            ];
        }

        $controlOptions = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][value]',
            [
                'options' => $options,
                'value'   => $this->value,
            ]
        );

        $compareOptions = $this->getSelectCompareControl(
            $this->compare,
            [
                pocketlistsProPluginComparision::TYPE_EQ,
                pocketlistsProPluginComparision::TYPE_NEQ,
//                pocketlistsProPluginComparision::TYPE_IN,
            ]
        );

        return <<<HTML
{$this->getHiddenIdentifierControl()}
{$compareOptions}
{$controlOptions}
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
        $this->value = $json['value'];
        $this->compare = $json['compare'];

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
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
        ];
    }

    /**
     * @return shopPluginModel
     */
    protected function getShopPluginModel()
    {
        if (static::$shopPluginModel === null) {
            static::$shopPluginModel = new shopPluginModel();
        }

        return static::$shopPluginModel;
    }
}
