<?php

/**
 * Class pocketlistsProPluginActivityContactAction
 */
class pocketlistsProPluginActivityContactAction extends pocketlistsProPluginActivityActivityAction
{
    /**
     * @var bool
     */
    protected $lazy = false;

    /**
     * @var string
     */
    protected $type = 'Contact';

    /**
     * @return pocketlistsLog[]
     * @throws pocketlistsAssertException
     * @throws waDbException
     * @throws waException
     */
    protected function getLogs()
    {
        /** @var pocketlistsLogFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsLog::class);

        /** @var pocketlistsContact $contact */
        $contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($this->entity_id);

        return $factory
            ->setOffset($this->offset * self::LIMIT)
            ->setLimit(self::LIMIT)
            ->findLastForContact($contact, false);
    }
}
