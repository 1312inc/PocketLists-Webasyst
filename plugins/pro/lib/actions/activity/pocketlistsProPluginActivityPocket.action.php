<?php

/**
 * Class pocketlistsProPluginActivityPocketAction
 */
class pocketlistsProPluginActivityPocketAction extends pocketlistsProPluginActivityActivityAction
{
    /**
     * @var string
     */
    protected $type = 'Pocket';

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

        $resolver = new pocketlistsEntityResolver();
        /** @var pocketlistsPocket $pocket */
        $pocket = $resolver->getEntityById(pocketlistsPocket::class, $this->entity_id);

        return $factory
            ->setOffset($this->offset * self::LIMIT)
            ->setLimit(self::LIMIT)
            ->findLastForPocket($pocket);
    }
}
