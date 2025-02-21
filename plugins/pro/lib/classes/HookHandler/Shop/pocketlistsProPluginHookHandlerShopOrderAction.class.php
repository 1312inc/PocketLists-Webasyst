<?php


class pocketlistsProPluginHookHandlerShopOrderAction
{
    public function backendOrderActionHandler(array $action)
    {
        if (empty($action['action_id']) || empty($action['order_id'])) {
            return;
        }

        // находим все события, связанные с этим действием

        // каждое событие проверяем подходит ли оно под указанные условия

        // если match, то выполняем все автоматизации, связанны с этим событием

        switch ($action['action_id']) {
        }
    }
}
