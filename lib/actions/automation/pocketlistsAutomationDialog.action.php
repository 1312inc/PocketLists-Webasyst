<?php

/**
 * Class pocketlistsAutomationDialogAction
 */
class pocketlistsAutomationDialogAction extends pocketlistsViewAction
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsLogicException
     * @throws waException
     */
    public function preExecute()
    {
        parent::preExecute();

        if (!pl2()->getLinkedApp('shop')->isEnabled()) {
            throw new pocketlistsLogicException(_wp('Shop-Script app is not installed'));
        }
    }

    /**
     * @param $params
     * @return void
     * @throws pocketlistsAssertException
     * @throws pocketlistsNoShopActionException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $actionId = waRequest::get('event', '', waRequest::TYPE_STRING_TRIM);
        $automationId = waRequest::get('id', 0, waRequest::TYPE_INT);

        /** @var pocketlistsAutomationFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsAutomation::class);
        if ($automationId) {
            $automation = $factory->findById($automationId);
            pocketlistsAssert::instance($automation, pocketlistsAutomation::class);
        } else {
            $automation = $factory->createNew();
            $rules = [
                (new pocketlistsAutomationRuleShopAction())->load(['value' => $actionId]),
                new pocketlistsAutomationRuleShopPayment(),
                new pocketlistsAutomationRuleShopShipping(),
                new pocketlistsAutomationRuleShopAmount(),
                new pocketlistsAutomationRuleShopState(),
                new pocketlistsAutomationRuleShopStorefront(),
                new pocketlistsAutomationRuleShopCustomerGroup(),
            ];
            $automation->setRules($rules)->setAction(new pocketlistsCreateItemAction());
        }

        $this->setTemplate('templates/actions/settings/SettingsAutomationDialog.html');
        $this->view->assign([
            'automation' => $automation
        ]);
    }
}