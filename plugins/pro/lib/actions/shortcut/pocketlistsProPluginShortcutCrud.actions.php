<?php

/**
 * Class pocketlistsProPluginShortcutCrudActions
 */
class pocketlistsProPluginShortcutCrudActions extends pocketlistsProPluginAbstractEntityCrudActions
{
    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Shortcut';
    }

    /**
     * @inheritDoc
     */
    protected function getFactory()
    {
        return pl2()->getEntityFactory(pocketlistsProPluginShortcut::class);
    }
}
