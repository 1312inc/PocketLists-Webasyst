<?php

/**
 * Class pocketlistsProPluginWaEventListener
 */
class pocketlistsProPluginWaEventListener
{
    /**
     * @param pocketlistsEvent $event
     *
     * @return array
     * @throws waException
     */
    public function onEntityInsertBefore(pocketlistsEvent $event)
    {
        if ($event->getObject() instanceof pocketlistsItem) {
            return (new pocketlistsProPluginItemEventListener())->onInsert($event);
        }

        return [];
    }

    /**
     * @param pocketlistsEvent $event
     *
     * @return array
     * @throws waException
     */
    public function onEntityUpdateBefore(pocketlistsEvent $event)
    {
        if ($event->getObject() instanceof pocketlistsItem) {
            return (new pocketlistsProPluginItemEventListener())->onUpdate($event);
        }

        return [];
    }

    /**
     * @param array $params
     */
    public function onOrderAction(array $params)
    {
        try {
            pocketlistsLogger::debug('in order action handler');

            $order = new shopOrder($params['order_id']);
            /** @var shopWorkflowAction $action */
            $action = (new shopWorkflow())->getActionById($params['action_id']);

            $automationEvent = new pocketlistsProPluginAutomationShopOrderActionEvent($order, $action);
            $automationEvent->applyAutomations();
        } catch (Exception $ex) {
            pocketlistsLogger::error(
                sprintf("PRO: Automation error. %s\n%s", $ex->getMessage(), $ex->getTraceAsString())
            );
        }
    }
}
