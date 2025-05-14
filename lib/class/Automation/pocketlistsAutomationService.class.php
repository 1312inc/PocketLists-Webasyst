<?php

/**
 * Class pocketlistsAutomationService
 */
class pocketlistsAutomationService
{
    const GROUP_SHOP = 'shop';

    /**
     * @param string $group
     *
     * @return pocketlistsAutomationSettingsDto[]
     */
    public function getAvailableEventsForGroup($group = self::GROUP_SHOP)
    {
        if (!wa()->appExists('shop')) {
            return [];
        }

        wa('shop');

        /** @see shopSettingsNotificationsAction::getEvents * */
        $actions = (new shopWorkflow())->getAllActions();
        $events = [];
        /**
         * @var shopWorkflowAction $action
         */
        foreach ($actions as $action) {
            $id = $action->getId();
            $name = $action->getName();

            $eventDto = new pocketlistsAutomationSettingsDto();
            $eventDto->id = $id;
            $eventDto->name = $name;
            $eventDto->options = $action->getOptions();

            $events['shop.' . $id] = $eventDto;
        }

        $id1312 = uniqid('1312_', true);
        $events['shop.' . $id1312] = new pocketlistsAutomationSettingsDto();
        $events['shop.' . $id1312]->id = $id1312;
        $events['shop.' . $id1312]->name = _w('== DELETED ACTION ==');

        return $events;
    }

    public function getAvailableRules($group = self::GROUP_SHOP)
    {
        $rules = [
            new pocketlistsAutomationRuleShopPayment(),
        ];

        return $rules;
    }

    /**
     * @param string $identifier
     * @param array  $data
     *
     * @return pocketlistsAutomationRuleInterface
     * @throws pocketlistsLogicException
     * @throws pocketlistsNoShopActionException
     */
    public function createRule($identifier, array $data)
    {
        switch ($identifier) {
            case pocketlistsAutomationRuleShopAction::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopAction())->load($data);

            case pocketlistsAutomationRuleShopAmount::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopAmount())->load($data);

            case pocketlistsAutomationRuleShopCustomerGroup::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopCustomerGroup())->load($data);

            case pocketlistsAutomationRuleShopPayment::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopPayment())->load($data);

            case pocketlistsAutomationRuleShopShipping::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopShipping())->load($data);

            case pocketlistsAutomationRuleShopStorefront::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopStorefront())->load($data);

            case pocketlistsAutomationRuleShopState::IDENTIFIER:
                return (new pocketlistsAutomationRuleShopState())->load($data);
        }
    }
}