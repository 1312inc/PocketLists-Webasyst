<?php

/**
 * Class pocketlistsProPluginAutomationService
 */
class pocketlistsProPluginAutomationService
{
    const GROUP_SHOP = 'shop';

    /**
     * @param string $group
     *
     * @return array
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
//            $name = $action->getOption('log_record');
//            if (!$name) {
                $name = $action->getName();
//            }

            $events['order.'.$id] = [
                'id'      => $id,
                'name'    => $name,
                'options' => $action->getOptions(),
            ];
        }

        return $events;
    }
}