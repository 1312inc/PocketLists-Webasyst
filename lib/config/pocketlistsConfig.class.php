<?php

/**
 * Class pocketlistsConfig
 */
class pocketlistsConfig extends waAppConfig
{
    const API_CLIENT_ID = 'POCKETLISTS-WEB-SPA';
    const API_TOKEN_SCOPE = 'pocketlists';

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
     * @var pocketlistsAppLinkInterface[]
     */
    protected $linkers;

    /**
     * @var pocketlistsAppLinkInterface
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
     * @var pocketlistsEntityCounter
     */
    protected $entityCounter;

    /**
     * @var pocketlistsLogService
     */
    protected $logService;

    /**
     * @var pocketlistsEventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var pocketlistsAutomationService
     */
    protected $automation;

    /**
     * @var pocketlistsCronManager
     */
    protected $cronManager;

    /**
     * @param string $type
     *
     * @return waCache
     */
    public function getCache($type = 'default')
    {
        if ($this->cache === null) {
            $this->cache = parent::getCache($type)
                ?: new waCache(
                    new waFileCacheAdapter(['type' => 'file']),
                    'pocketlists'
                );
        }

        return $this->cache;
    }

    /**
     * @return pocketlistsEventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new pocketlistsEventDispatcher(
                new pocketlistsListenerProvider()
            );
        }

        return $this->eventDispatcher;
    }

    /**
     * @param pocketlistsEvent $event
     *
     * @return array
     */
    public function waDispatchEvent(pocketlistsEvent $event)
    {
        return wa('pocketlists')->event($event->getName(), $event);
    }

    /**
     * @param string      $eventName
     * @param object|null $object
     * @param array       $params
     *
     * @return pocketlistsListenerResponseInterface
     */
    public function event($eventName, $object = null, $params = [])
    {
        return $this->getEventDispatcher()->dispatch(new pocketlistsEvent($eventName, $object, $params));
    }

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
     * @return pocketlistsLogService
     * @throws waException
     */
    public function getLogService()
    {
        if ($this->logService === null) {
            $this->logService = new pocketlistsLogService();
        }

        return $this->logService;
    }

    /**
     * @param $entity
     *
     * @return pocketlistsItemLinkFactory|pocketlistsItemFactory|pocketlistsListFactory|pocketlistsContactFactory|pocketlistsPocketFactory|pocketlistsCommentFactory|pocketlistsAttachmentFactory|pocketlistsItemLinkFactory|pocketlistsNotificationFactory|pocketlistsLogFactory
     * @throws waException
     */
    public function getEntityFactory($entity)
    {
        if (isset($this->factories[$entity])) {
            return $this->factories[$entity]->resetLimitAndOffset();
        }

        $factoryClass = sprintf('%sFactory', $entity);

        if (!class_exists($factoryClass)) {
            return $this->factories[''];
        }

        $this->factories[$entity] = new $factoryClass();

        return $this->factories[$entity];
    }


    /**
     * @param $entity
     *
     * @return pocketlistsModel|pocketlistsItemLinkModel|pocketlistsItemModel|pocketlistsListModel|pocketlistsLogModel
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
                'kmStatistics',
            ],
        ];
        $path_root = waConfig::get('wa_path_root');
        foreach ($customClasses as $path => $classes) {
            foreach ($classes as $class) {
                $file = $path_root.DIRECTORY_SEPARATOR.$path.$class.'.class.php';
                if (!class_exists($class, false) && file_exists($file)) {
                    waAutoload::getInstance()->add($class, $path.$class.'.class.php');
                }
            }
        }

        $this->models[''] = new pocketlistsModel();
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
    public function onCount($onlycount = false)
    {
        $as_model = new waAppSettingsModel();
        $last_date = $as_model->get(pocketlistsHelper::APP_ID, 'last_repeat_list_cron_date');
        if ($last_date < date('Y-m-d')) {
            try {
                $as_model->set(pocketlistsHelper::APP_ID, 'last_repeat_list_cron_date', date('Y-m-d'));
                pocketlistsRepetitions::repeatLists();
            } catch (Exception $ex) {
                pocketlistsLogger::error(
                    sprintf(
                        'Error on repeating checklists, onCount. Error: %s. Trace: %s',
                        $ex->getMessage(),
                        $ex->getTraceAsString()
                    ),
                    'repeating.log'
                );
            }
        }

        try {
            /** @var pocketlistsItemModel $item_model */
            $item_model = wa(pocketlistsHelper::APP_ID)->getConfig()->getModel(pocketlistsItem::class);
            $last_update_time = wa()->getUser()->getSettings(pocketlistsHelper::APP_ID, 'last_updateCalcPriority', 0);
            if (time() - $last_update_time > 300) {
                $item_model->updateCalcPriority();
            }
            $count = $this->getUser()->getAppCount();
            $pocketlists_path = sprintf('%spocketlists?module=backendJson&action=', pl2()->getBackendUrl(true));

            $css = '';
            if (!$count) {
                $css = <<<HTML
<style>
    [data-app="pocketlists"] .indicator,
    [data-app="pocketlists"] .badge { display: none !important; }
</style>
HTML;
            }
            $script = <<<HTML
<script>
(function () {
    'use strict';

    try {
        $.post('{$pocketlists_path}sendNotifications', function (r) {
            if (r.status === 'ok') {
                var sent = parseInt(r.data);
                sent && console.log('pocketlists: notification send ' + sent);
            } else {
                console.log('pocketlists: notification send error ' + r.error);
            }
        }, 'json')
        .fail(function () {
            console.log('pocketlists: notification send internal error');
        });
        
        $.post('{$pocketlists_path}sendDirectNotifications', function (r) {
            if (r.status === 'ok') {
                if (window['pocketlistsAlertBox'] && r.data) {
                    $.each(r.data, function () {
                        var alertbox = new pocketlistsAlertBox('#pl2-notification-area', {
                            closeTime: 120000,
                            persistent: true,
                            hideCloseButton: false
                        });
                        alertbox.show(this);
                    });
                }
            } else {
                console.log('pocketlists: notification send error ' + r.error);
            }
        }, 'json')
        .fail(function () {
            console.log('pocketlists: notification send internal error');
        });
    } catch (e) {
        console.log('pocketlists: notification send exception ', e);
    }
})()
</script>
HTML;

            return $onlycount ? $count : $count.$css.$script;
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
        return $this->getAppConfigPath('linked_apps');
    }

    public function getUtf8mb4ColumnsPath()
    {
        return $this->getAppConfigPath('utf8mb4');
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
     * @return pocketlistsAppLinkInterface|pocketlistsAppLinkInterface[]
     * @throws waException
     */
    public function getLinkedApp($app = '')
    {
        if ($this->linkers === null) {
            $this->linkers = [];
            foreach ($this->getLinkedApps() as $entity) {
                $class = sprintf('pocketlistsAppLink%s', ucfirst($entity));
                if (class_exists($class)) {
                    $class = new $class();
                    if ($class instanceof pocketlistsAppLinkInterface && $class->isEnabled()) {
                        $this->linkers[$entity] = $class;
                    }
                }
            }
        }

        if (!empty($app) && !isset($this->linkers[$app])) {
            if ($this->fakeLinker === null) {
                $this->fakeLinker = new pocketlistsAppLinkFake();
            }

            return $this->fakeLinker;
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

    /**
     * @return pocketlistsEntityCounter
     */
    public function getEntityCounter()
    {
        if ($this->entityCounter === null) {
            $this->entityCounter = new pocketlistsEntityCounter();
        }

        return $this->entityCounter;
    }

    /**
     * @return array
     */
    public function getDefaultViewVars()
    {
        return [
            'backend_url'          => $this->getBackendUrl(true),
            'plurl'                => wa()->getAppUrl(pocketlistsHelper::APP_ID),
            'current_user'         => $this->getUser(),
            'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
            'wa_app_static_url'    => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
            'pl2'                  => pl2(),
        ];
    }

    /**
     * @param string $template
     *
     * @return string
     * @throws waException
     */
    public function getUI2TemplatePath($template = null, $app = null)
    {
        $suffix = wa()->whichUI($app) === '1.3' ? '-legacy' : '';

        return $template ? sprintf($template, $suffix) : $suffix;
    }

    public function appExists($app)
    {
        return wa()->appExists($app);
    }

    private function registerGlobal()
    {
        if (!function_exists('pl2')) {
            /**
             * @return pocketlistsConfig
             */
            function pl2()
            {
                return wa(pocketlistsHelper::APP_ID)->getConfig();
            }
        }
    }

    public function getAutomationService()
    {
        if ($this->automation === null) {
            $this->automation = new pocketlistsAutomationService();
        }

        return $this->automation;
    }

    /**
     * @return pocketlistsCronManager
     */
    public function getCronManager()
    {
        if ($this->cronManager === null) {
            $this->cronManager = new pocketlistsCronManager();
        }

        return $this->cronManager;
    }
}
