<?php

/**
 * Class pocketlistsProPluginApplyDelayedAutomationsCli
 */
class pocketlistsProPluginApplyDelayedAutomationsCli extends waCliController
{
    /**
     * @param null $params
     *
     * @throws waException
     */
    public function run($params = null)
    {
        /** @var pocketlistsProPluginDelayedAutomationFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginDelayedAutomation::class);
        $delayed = $factory->findByNewByTime(date('Y-m-d H:i:s'));
        pocketlistsLogger::debug(sprintf('Start execute delayed automations. Found %d to process', count($delayed)));
        if (!$delayed) {
            pocketlistsLogger::debug('Nothing to do. Bye bye :*');

            return;
        }

        $idsToProcess = [];
        foreach ($delayed as $delayedAutomation) {
            $idsToProcess[] = $delayedAutomation->getId();
        }

        $factory->getModel()->updateById($idsToProcess, ['status' => pocketlistsProPluginDelayedAutomation::STATUS_PROCESS]);
        pocketlistsLogger::debug('Status of delayed automations updated to 1 (process)');

        foreach ($delayed as $delayedAutomation) {
            try {
                pocketlistsLogger::debug(sprintf('Start delayed automations %s', $delayedAutomation->getId()));

                $delayedAutomation->setStatus(pocketlistsProPluginDelayedAutomation::STATUS_PROCESS);
                $factory->update($delayedAutomation);

                $automation = $delayedAutomation->getAutomation();
                if (!$automation->isEnabled()) {
                    pocketlistsLogger::debug(sprintf('Automation %d is disabled', $automation->getId()));

                    continue;
                }

                $data = $delayedAutomation->getEventData();
                $orderId = ifset($data, 'order_id', null);
                $order = new shopOrder($orderId);
                if (!$order instanceof shopOrder) {
                    throw new pocketlistsLogicException(
                        sprintf('No order %s for delayed automation %s', $orderId, $delayedAutomation->getId())
                    );
                }

                $actionId = ifset($data, 'action_id', null);
                /** @var shopWorkflowAction $action */
                $action = (new shopWorkflow())->getActionById($actionId);
                if (!$action instanceof waWorkflowAction) {
                    throw new pocketlistsLogicException(
                        sprintf(
                            'No workflow action %s for delayed automation %s',
                            $actionId,
                            $delayedAutomation->getId()
                        )
                    );
                }

                $automationEvent = new pocketlistsProPluginAutomationShopOrderActionEvent($order, $action);
                $result = $automationEvent->executeAutomation($automation, $delayedAutomation->getEventData());
                if ($result->status) {
                    $delayedAutomation->setStatus(pocketlistsProPluginDelayedAutomation::STATUS_OK);
                    if ($result->data instanceof pocketlistsItem) {
                        $delayedAutomation->setItemId($result->data->getId());
                    }
                } else {
                    $delayedAutomation
                        ->setError(sprintf('Failed rule %s', $automationEvent->getFailedRule()))
                        ->setStatus(pocketlistsProPluginDelayedAutomation::STATUS_ERROR)
                    ;
                }
            } catch (Exception $ex) {
                pocketlistsLogger::error(
                    sprintf("PRO: Delayed automation error. %s\n%s", $ex->getMessage(), $ex->getTraceAsString())
                );
                $delayedAutomation
                    ->setError($ex->getMessage())
                    ->setStatus(pocketlistsProPluginDelayedAutomation::STATUS_ERROR)
                ;
            } finally {
                $factory->update($delayedAutomation);

                pocketlistsLogger::debug(
                    sprintf(
                        'End delayed automations %s. Status %s. Error: %s',
                        $delayedAutomation->getId(),
                        $delayedAutomation->getStatus(),
                        $delayedAutomation->getError()
                    )
                );
            }
        }
        pocketlistsLogger::debug('End execute delayed automations');

        pocketlistsProPlugin::getInstance()->getCronManager()
            ->saveLastRunCronJob(pocketlistsProPluginCronManager::APPLY_DELAYED_AUTOMATIONS);
    }
}
