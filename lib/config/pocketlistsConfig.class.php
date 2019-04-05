<?php

/**
 * Class pocketlistsConfig
 */
class pocketlistsConfig extends waAppConfig
{
    /**
     * @var array
     */
    protected $factories = [];

    /**
     * @var array
     */
    protected $models = [];

    /**
     * @var array
     */
    protected $repositories = [];

    /**
     * @var pocketlistsItemLinkInterface[]
     */
    protected $linkers;

    /**
     * @var pocketlistsItemLinkInterface
     */
    protected $fakeLinker;

    /**
     * @var pocketlistsUser
     */
    protected $user;

    /**
     * @var pocketlistsHydratorInterface
     */
    protected $hydrator;

    /**
     * @return pocketlistsHydratorInterface
     */
    public function getHydrator()
    {
        if ($this->hydrator === null) {
            $this->hydrator = new pocketlistsHydrator();
        }

        return $this->hydrator;
    }

    /**
     * @param $entity
     *
     * @return pocketlistsItemLinkFactory|pocketlistsItemFactory|pocketlistsListFactory|pocketlistsContactFactory
     * @throws waException
     */
    public function getEntityFactory($entity)
    {
        if (isset($this->factories[$entity])) {
            return $this->factories[$entity];
        }

        $factoryClass = sprintf('%sFactory', $entity);

        if (!class_exists($factoryClass)) {
            return $this->factories['']->setEntity($entity);
        }

        $this->factories[$entity] = new $factoryClass();
        $this->factories[$entity]->setEntity($entity);

        return $this->factories[$entity];
    }


    /**
     * @param $entity
     *
     * @return kmModelExt
     * @throws waException
     */
    public function getModel($entity = false)
    {
        if ($entity === false) {
            return $this->models[''];
        }

        if (isset($this->models[$entity])) {
            return $this->models[$entity];
        }

        $modelClass = sprintf('%sModel', $entity);

        if (!class_exists($modelClass)) {
            throw new waException(sprintf('No model class for %s', $entity));
        }

        $this->models[$entity] = new $modelClass();

        return $this->models[$entity];
    }

    /**
     * @param $entity
     *
     * @return
     * @throws waException
     */
    public function getEntityRepository($entity)
    {
        if (isset($this->repositories[$entity])) {
            return $this->repositories[$entity];
        }

        $repositoryClass = sprintf('%sRepository', $entity);

        if (!class_exists($repositoryClass)) {
            throw new waException(sprintf('No repository class for %s', $entity));
        }

        $this->repositories[$entity] = new $repositoryClass();

        return $this->repositories[$entity];
    }

    public function init()
    {
        parent::init();

        $customClasses = [
            'wa-apps/pocketlists/lib/vendor/km/' => [
                'kmStorage',
                'kmModelExt',
                'kmModelStorage',
                'kmStatistics',
            ],
        ];

        foreach ($customClasses as $path => $classes) {
            foreach ($classes as $class) {
                $file = wa()->getAppPath('lib/vendor/km/'.$class.'.class.php', 'pocketlists');
                if (!class_exists($class, false) && file_exists($file)) {
                    waAutoload::getInstance()->add($class, $path.$class.'.class.php');
                }
            }
        }

        $this->models[''] = new kmModelExt();
        $this->factories[''] = new pocketlistsFactory();

        $this->registerGlobal();
    }

    public function onInit()
    {
        $wa = wa();
        $id = $wa->getUser()->getId();
        if ($id && ($wa->getApp() == 'pocketlists') && ($wa->getEnv() == 'backend')) {
            $this->setCount($this->onCount());
        }
    }

    /**
     * @return int|null
     * @throws waException
     */
    public function onCount()
    {
        try {
            /** @var pocketlistsItemModel $itemModel */
            $itemModel = wa(pocketlistsHelper::APP_ID)->getConfig()->getModel(pocketlistsItem::class);

            $itemModel->updateCalcPriority();

            return $this->getUser()->getAppCount();
        } catch (Exception $ex) {
            pocketlistsHelper::logError('onCount error', $ex);
        }

        return null;
    }

    public function checkRights($module, $action)
    {
        return true;
    }

    public function explainLogs($logs)
    {
        $log_action = new pocketlistsLogAction();
        $logs = $log_action->explainLogs($logs);

        return $logs;
    }

    public function getCronJob($name = null)
    {
        static $tasks;
        if (!isset($tasks)) {
            $tasks = [];
            $path = $this->getAppConfigPath('cron');
            if (file_exists($path)) {
                $tasks = include($path);
            } else {
                $tasks = [];
            }
        }

        return $name ? (isset($tasks[$name]) ? $tasks[$name] : null) : $tasks;
    }

    public function getLinkedAppConfigPath()
    {
        return wa()->getAppPath('lib/config/linked_apps.php', pocketlistsHelper::APP_ID);
    }

    public function getLinkedApps()
    {
        $apps = require $this->getLinkedAppConfigPath();
        $linked = [];

        if (!is_array($apps)) {
            return $linked;
        }

        foreach ($apps as $app => $info) {
            if (!is_array($info)) {
                $linked[] = $app;
            } elseif (!empty($info['enable'])) {
                $linked[] = $app;
            }
        }

        return $linked;
    }

    /**
     * @param string $app
     *
     * @return pocketlistsItemLinkInterface|pocketlistsItemLinkInterface[]
     * @throws waException
     */
    public function getLinkedApp($app = '')
    {
        if ($this->linkers === null) {
            foreach ($this->getLinkedApps() as $entity) {
                $class = sprintf('pocketlistsItemLink%s', ucfirst($entity));
                if (class_exists($class)) {
                    $class = new $class();
                    if ($class instanceof pocketlistsItemLinkInterface && $class->isEnabled()) {
                        $this->linkers[$entity] = $class;
                    }
                }
            }
        }

        if (!empty($app) && !isset($this->linkers[$app])) {
            if ($this->fakeLinker === null) {
                $this->fakeLinker = new pocketlistsItemLinkFake();
            }

            return $this->fakeLinker;
//            throw new waException('No linked class for app ' . $app);
        }

        return empty($app) ? $this->linkers : $this->linkers[$app];
    }

    /**
     * @return pocketlistsUser
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = new pocketlistsUser(wa()->getUser());
        }

        return $this->user;
    }

    private function registerGlobal()
    {
        if (!function_exists('pl2')) {
            function pl2()
            {
                return wa(pocketlistsHelper::APP_ID)->getConfig();
            }
        }
    }
}
