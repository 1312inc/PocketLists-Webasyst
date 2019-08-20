<?php

/**
 * Class pocketlistsProPluginAutomationShopOrderRule
 */
class pocketlistsProPluginAutomationShopOrderRule extends kmAbstractAutomationRule
{
    const TYPE_STATUS     = 'status';
    const TYPE_PAYMENT    = 'payment';
    const TYPE_AMOUNT     = 'amount';
    const TYPE_STOREFRONT = 'storefront';


    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $compare;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var shopOrder
     */
    private $order;

    public function __construct(shopOrder $order)
    {
        $this->order = $order;
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
        // TODO: Implement jsonSerialize() method.
    }

    /**
     * @param kmAutomationRuleInterface $rule
     *
     * @return kmAutomationRuleInterface
     */
    public function setNext(kmAutomationRuleInterface $rule)
    {
        // TODO: Implement setNext() method.
    }

    /**
     * @return bool
     * @throws pocketlistsLogicException
     */
    public function match()
    {
        $sourceVal = null;
        switch ($this->id) {
            case self::TYPE_AMOUNT:
                $sourceVal = $this->order->total;
                break;

            case self::TYPE_PAYMENT:
                $sourceVal = $this->order->payment_name;
                break;
        }

        return kmComparision::compare($sourceVal, $this->value, $this->compare);
    }

    /**
     * @return string
     */
    public function buildHtml()
    {
        // TODO: Implement buildHtml() method.
    }

    /**
     * @return string
     */
    public function editHtml()
    {
        // TODO: Implement editHtml() method.
    }

    /**
     * @param array $json
     */
    public function load(array $json)
    {
        foreach (['id', 'compare', 'value'] as $prop) {
            if (array_key_exists($prop, $json)) {
                $this->{$prop} = $json[$prop];
            }
        }
    }
}