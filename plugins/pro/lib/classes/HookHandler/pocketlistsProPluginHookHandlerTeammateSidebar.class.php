<?php

/**
 * Class pocketlistsProPluginHookHandlerTeammateSidebar
 */
class pocketlistsProPluginHookHandlerTeammateSidebar extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param pocketlistsEvent $event
     *
     * @return string
     * @throws pocketlistsAssertException
     */
    public function handle($event = null)
    {
        /** @var pocketlistsContact $contact */
        $contact = $event->getObject();
        pocketlistsAssert::instance($contact, pocketlistsContact::class);

        $action = new pocketlistsProPluginActivityContactAction(
            [
                'entity_id' => $contact->getId(),
            ]
        );

        return $action->display(false);
    }
}
