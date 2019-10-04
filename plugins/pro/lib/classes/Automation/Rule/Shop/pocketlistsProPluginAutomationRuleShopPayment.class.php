<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopPayment
 */
class pocketlistsProPluginAutomationRuleShopPayment extends pocketlistsProPluginAutomationRuleAbstract
{
    const IDENTIFIER = 'payment';

    /**
     * @var array
     */
    protected $possibleValues;

    /**
     * @var string
     */
    protected $value;

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
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            $model = new shopPluginModel();
            $instances = $model->listPlugins(shopPluginModel::TYPE_PAYMENT, ['all' => true,]);
//        foreach ($instances as &$instance) {
//            $instance['installed'] = isset($plugins[$instance['plugin']]);
//
//            unset($instance);
//        }
            foreach ($instances as $instance) {
                $this->possibleValues[$instance['plugin'].$instance['id']] = $instance['name'];
            }
        }

        return $this->possibleValues;
    }

    /**
     * @return string
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
        return <<<HTML
    show html here 
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $options = [['title' => '', 'value' => '']];
        foreach ($this->getPossibleValues() as $possibleValue) {
            $options[] = [
                'title' => $possibleValue,
                'value' => $possibleValue,
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
}