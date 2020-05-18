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
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    private $app;

    /**
     * @var array
     */
    private $queries = [];

    /**
     * @var array
     */
    private $statQueries = [];

    /**
     * @var string
     */
    private $statQueriesFileCache = '';

    /**
     * @param string $app
     *
     * @return kmStatistics
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

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
     * @return kmStatistics
     */
    public static function get()
    {
        return self::getInstance();
    }

    /**
     * kmMysqlStat constructor.
     */
    protected function __construct()
    {
        $this->timeStart = microtime(true);
        $this->timePrev = $this->timeStart;

        $this->uuid = microtime();

        $this->storage = new kmStorage(
            [
                'queries' => $this->queries,
                'timing'  => [
                    0 => [],
                ],
            ]
        );

        $this->statQueriesFileCache = __DIR__.'/kmstatqueries.php';
        if (file_exists($this->statQueriesFileCache)) {
            $this->statQueries = require($this->statQueriesFileCache);
        }
    }

    public function exportStatQueries()
    {
        foreach ($this->statQueries as &$statQuery) {
            uasort(
                $statQuery,
                function ($v, $v2) {
//                    return $v['time_per_query'] < $v2['time_per_query'] ? 1 : ($v['time_per_query'] > $v2['time_per_query'] ? -1 : 0);
                    return $v['total_time'] < $v2['total_time'] ? 1 : ($v['total_time'] > $v2['total_time'] ? -1 : 0);
                }
            );
        }
        unset($statQuery);

        waUtils::varExportToFile($this->statQueries, $this->statQueriesFileCache);
    }

    /**
     * @param string $query
     */
    public function addQuery($query)
    {
        if ($this->app) {
            $this->queries[$this->app][$this->getMethod()] = preg_replace('/\s+/', ' ', $query);
        }
    }

    /**
     * @param $sql
     * @param $callback
     *
     * @return mixed
     */
    public function addQueryWithTime($sql, $callback)
    {
        $timea = microtime(true);
        $return = $callback();

        $this->app = wa()->getApp();
//        if (!$this->app) {
//            return $return;
//        }

        $this->addQuery($sql);
        $this->addStatQuery($sql, microtime(true) - $timea);

        return $return;
    }

    /**
     * @param string $query
     * @param float  $executionTime
     */
    public function addStatQuery($query, $executionTime)
    {
        if ($this->app) {
            $method = $this->getMethod();
            $query = preg_replace('/\s+/', ' ', $query);
            $queryKey = md5($query);
            if (!isset($this->statQueries[$this->app][$queryKey])) {
                $this->statQueries[$this->app][$queryKey] = [
                    'path'           => [],
                    'total_time'     => 0,
                    'count'          => 0,
                    'last_query'     => [],
                    'time_per_query' => 0,
                ];
            }

            $this->statQueries[$this->app][$queryKey]['count']++;
            $this->statQueries[$this->app][$queryKey]['total_time'] += $executionTime;
            $this->statQueries[$this->app][$queryKey]['last_query'] = $query;
            $this->statQueries[$this->app][$queryKey]['time_per_query'] = $this->statQueries[$this->app][$queryKey]['total_time'] / $this->statQueries[$this->app][$queryKey]['count'];
            if (!isset($this->statQueries[$this->app][$queryKey]['path'][$method])) {
                $this->statQueries[$this->app][$queryKey]['path'][$method] = [
                    'time'  => 0,
                    'count' => 0,
//                    'method' => $method,
                ];
            }
            $this->statQueries[$this->app][$queryKey]['path'][$method]['time'] += $executionTime;
            $this->statQueries[$this->app][$queryKey]['path'][$method]['count']++;
        }
    }

    /**
     * @return mixed
     */
    public function getQueriesCount()
    {
        return count($this->queries);
    }

    /**
     * @param string $app
     *
     * @return array
     */
    public function getQueries($app = '')
    {
        return isset($this->queries[$app]) ? $this->queries[$app] : $this->queries;
    }

    /**
     * @param string $app
     *
     * @return array
     */
    public function getStatQueries($app = '')
    {
        return isset($this->statQueries[$app]) ? $this->statQueries[$app] : $this->statQueries;
    }

    /**
     * @return float
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        $plitem = [];
        foreach (debug_backtrace() as $i => $item) {
            if (isset($item['class']) && strpos($item['class'], 'pocketlists') === 0) {
                $plitem[] = sprintf('%s%s%s::%s', $item['class'], $item['type'], $item['function'], ifset($item, 'line', 0));
            }
        }

        return $plitem ? implode('|', array_reverse($plitem)) : '';
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
            $key = ($method === true ? $this->getMethod() : $method).'::'.$key;
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

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function __destruct()
    {
//        $this->exportStatQueries();
    }
}
