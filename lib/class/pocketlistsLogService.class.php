<?php


/**
 * Class pocketlistsLogService
 */
class pocketlistsLogService
{
    /**
     * @var pocketlistsLogFactory
     */
    private $factory;

    /**
     * pocketlistsLogService constructor.
     *
     * @throws waException
     */
    public function __construct()
    {
        $this->factory = pl2()->getEntityFactory(pocketlistsLog::class);
    }

    /**
     * @return pocketlistsLogFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param pocketlistsLog $log
     *
     * @return bool|waDbResultUpdate|null
     * @throws waException
     */
    public function add(pocketlistsLog $log)
    {
        $ok = $this->factory->save($log);

        if ($ok) {
            pl2()->getEventDispatcher()->dispatch(
                new pocketlistsEvent(
                    pocketlistsEventStorage::LOG_INSERT,
                    $log
                )
            );
        }

        return $ok;
    }

    /**
     * @param pocketlistsLog $log
     */
    public function explain(pocketlistsLog $log)
    {

    }
}
