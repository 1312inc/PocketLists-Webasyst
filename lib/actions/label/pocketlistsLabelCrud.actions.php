<?php

/**
 * Class pocketlistsLabelCrudActions
 */
class pocketlistsLabelCrudActions extends pocketlistsAbstractEntityCrudActions
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
        return pl2()->getEntityFactory(pocketlistsLabel::class);
    }
}