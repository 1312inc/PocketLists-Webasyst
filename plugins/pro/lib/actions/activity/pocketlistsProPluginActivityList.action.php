<?php

/**
 * Class pocketlistsProPluginActivityListAction
 */
class pocketlistsProPluginActivityListAction extends pocketlistsProPluginActivityActivityAction
{
    /**
     * @var bool
     */
    protected $lazy = false;

    /**
     * @var string
     */
    protected $type = 'List';

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
        /** @var pocketlistsList $list */
        $list = $resolver->getEntityById(pocketlistsList::class, $this->entity_id);

        return $factory
            ->setOffset($this->offset * self::LIMIT)
            ->setLimit(self::LIMIT)
            ->findLastForList($list);
    }
}
