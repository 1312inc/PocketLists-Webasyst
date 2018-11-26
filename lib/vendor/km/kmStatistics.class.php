<?php

/**
 * Class kmStatistics
 *
 * @method void query($query)
 * @method void timing($key, $method = true)
 */
class kmStatistics
{
    /**
     * @var kmStatistics
     */
    protected static $instance;

    /**
     * @var kmStorage
     */
    protected $storage;

    /**
     * @var float
     */
    protected $timeStart;

    /**
     * @var float
     */
    protected $timePrev;

    /**
     * @return kmStatistics
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * kmMysqlStat constructor.
     */
    protected function __construct()
    {
        $this->timeStart = microtime(true);
        $this->timePrev = $this->timeStart;

        $this->storage = new kmStorage(
            [
                'queries' => [],
                'timing'  => [
                    0 => [],
                ],
            ]
        );

    }

    /**
     * @param string $query
     */
    protected function addQuery($query)
    {
        $this->storage->queries[] = $query;
    }

    /**
     * @return mixed
     */
    public function getQueriesCount()
    {
        return count($this->storage->queries);
    }

    /**
     * @return mixed
     */
    public function getQueries()
    {
        return $this->storage->queries;
    }

    /**
     * @return float
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @param string $key
     * @param bool   $method
     */
    protected function addTiming($key, $method = true)
    {
        $toAdd = [
            round(microtime(true) - $this->timePrev, 4),
            round(microtime(true) - $this->timeStart, 4),
        ];

        if ($method !== false) {
            $key = ($method === true
                    ? debug_backtrace()[2]['class'].debug_backtrace()[2]['type'].debug_backtrace()[2]['function']
                    : $method).'::'.$key;
        }

        $timing = $this->storage['timing'];
        $timing[$key] = $toAdd;
        $this->storage['timing'] = $timing;

        $this->timePrev = microtime(true);
    }

    /**
     * @return array
     */
    public function getTiming()
    {
        return $this->storage['timing'];
    }

    public function getRunTime()
    {

    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws waException
     */
    public function __call($name, $arguments)
    {
        if (waSystemConfig::isDebug()) {
            $name = 'add'.ucfirst($name);
            if (method_exists($this, $name)) {
                return call_user_func_array([$this, $name], $arguments);
            }

            throw new waException(sprintf('No method %s found in %s', $name, __CLASS__));
        }
    }
}
