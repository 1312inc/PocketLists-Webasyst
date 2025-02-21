<?php

/**
 * Class pocketlistsProPluginLabelCrudActions
 */
class pocketlistsProPluginLabelCrudActions extends pocketlistsProPluginAbstractEntityCrudActions
{
    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Label';
    }

    /**
     * @inheritDoc
     */
    protected function getFactory()
    {
        return pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
    }
}
