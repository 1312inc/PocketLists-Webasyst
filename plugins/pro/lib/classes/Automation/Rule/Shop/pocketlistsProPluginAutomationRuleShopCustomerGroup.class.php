<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopCustomerGroup
 */
class pocketlistsProPluginAutomationRuleShopCustomerGroup extends pocketlistsProPluginAutomationRuleShop
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
            $categories = shopCustomer::getAllCategories();
            $this->possibleValues = array_combine(
                array_column($categories, 'id'),
                array_column($categories, 'name')
            );
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

        return !empty((new waContactCategoriesModel())->inCategory($order->contact_id, $this->value));
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

        $category = (new waContactCategoryModel())->getById($this->value);
        $categoryName = ifset($category, 'name', _wp('DELETED CATEGORY'));

        return <<<HTML
<strong>{$this->getLabel()} = {$categoryName}</strong>
HTML;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function editHtml()
    {
        $category = [['title' => _wp('any'), 'value' => '']];
        foreach ($this->getPossibleValues() as $id => $item) {
            $category[] = [
                'title' => $item,
                'value' => $id,
            ];
        }

        $category = waHtmlControl::getControl(
            waHtmlControl::SELECT,
            'data[rules]['.$this->getIdentifier().'][value]',
            [
                'value'   => $this->value,
                'options' => $category,
            ]
        );

        return <<<HTML
{$this->getHiddenIdentifierControl()}
{$category}
HTML;
    }

    /**
     * @param array $json
     *
     * @return pocketlistsProPluginAutomationRuleShopAction|pocketlistsProPluginSerializableInterface
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