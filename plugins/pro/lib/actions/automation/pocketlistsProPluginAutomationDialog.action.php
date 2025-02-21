<?php

/**
 * Class pocketlistsProPluginAutomationDialogAction
 */
class pocketlistsProPluginAutomationDialogAction extends pocketlistsViewAction
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
     * @param null|array $params
     *
     * @return mixed
     * @throws pocketlistsAssertException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $actionId = waRequest::get('event', '', waRequest::TYPE_STRING_TRIM);
        $automationId = waRequest::get('id', 0, waRequest::TYPE_INT);

        /** @var pocketlistsProPluginAutomationFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginAutomation::class);
        if ($automationId) {
            $automation = $factory->findById($automationId);
            pocketlistsAssert::instance($automation, pocketlistsProPluginAutomation::class);
        } else {
            $automation = $factory->createNew();
            $automation
                ->setRules([
                    (new pocketlistsProPluginAutomationRuleShopAction())->load(['value' => $actionId]),
                    new pocketlistsProPluginAutomationRuleShopPayment(),
                    new pocketlistsProPluginAutomationRuleShopShipping(),
                    new pocketlistsProPluginAutomationRuleShopAmount(),
                    new pocketlistsProPluginAutomationRuleShopState(),
                    new pocketlistsProPluginAutomationRuleShopStorefront(),
                    new pocketlistsProPluginAutomationRuleShopCustomerGroup(),
                ])
                ->setAction(new pocketlistsProPluginCreateItemAction());
        }

        $this->view->assign(['automation' => $automation]);
    }
}
