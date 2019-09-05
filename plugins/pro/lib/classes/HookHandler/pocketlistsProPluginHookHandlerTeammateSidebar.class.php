<?php

/**
 * Class pocketlistsProPluginHookHandlerTeammateSidebar
 */
class pocketlistsProPluginHookHandlerTeammateSidebar extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param pocketlistsContact $contact
     *
     * @return string
     */
    public function handle($contact = null)
    {
        $action = new pocketlistsProPluginActivityContactAction(
            [
                'entity_id' => $contact->getId(),
            ]
        );

        return $action->display(false);
    }
}
