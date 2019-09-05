<?php

/**
 * Class pocketlistsProPluginHookHandlerListAccesses
 */
class pocketlistsProPluginHookHandlerListAccesses extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param pocketlistsList $list
     *
     * @return string
     */
    public function handle($list = null)
    {
        $action = new pocketlistsProPluginActivityListAction(
            [
                'entity_id' => $list->getId(),
            ]
        );

        return $action->display(false);
    }
}
