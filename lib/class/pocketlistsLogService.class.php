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
//        wa()->event('lodAdd', $log);
        return $this->factory->save($log);
    }

    /**
     * @param pocketlistsLog $log
     */
    public function explain(pocketlistsLog $log)
    {

    }
}
