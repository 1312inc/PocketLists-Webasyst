<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopCustomerGroup
 *
 * @method array getValue()
 * @property array $value
 */
class pocketlistsProPluginAutomationRuleShopCustomerGroup extends pocketlistsProPluginAutomationRuleShop
{
    const IDENTIFIER = 'customer_group';

    /**
     * @var array
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

        $categories = (new waContactCategoryModel())->getByField('id', $this->value, true);
        $categoryNames = [];
        foreach ($categories as $category) {
            $categoryNames[] = ifset($category, 'name', '');
        }
        array_filter($categoryNames);
        $categoryNames = implode(', ', $categoryNames);

        return <<<HTML
<strong>{$this->getLabel()} = {$categoryNames}</strong>
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

        return <<<HTML
{$this->getHiddenIdentifierControl()}
{$this->getMultipleSelectControl($category)}
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
     * @return pocketlistsProPluginAutomationRuleShopAction|pocketlistsProPluginSerializableInterface
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
