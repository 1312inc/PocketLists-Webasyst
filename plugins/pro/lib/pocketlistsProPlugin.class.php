<?php

/**
 * Class pocketlistsProPlugin
 */
class pocketlistsProPlugin extends waPlugin
{
    const ID_ARR = [pocketlistsHelper::APP_ID, 'pro'];
    const ID_STR = pocketlistsHelper::APP_ID.'.pro';

    /**
     * @var waView
     */
    protected $view;

    /**
     * @var pocketlistsProPlugin
     */
    protected static $instance;

    /**
     * @var pocketlistsProPluginAutomationService
     */
    protected $automation;

    /**
     * @var pocketlistsProPluginCronManager
     */
    protected $cronManager;

    /**
     * @return pocketlistsProPlugin
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = wa(pocketlistsHelper::APP_ID)->getPlugin('pro');
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return waView
     */
    public function getView()
    {
        if ($this->view === null) {
            $this->view = wa()->getView();
        }

        return $this->view;
    }

    public function saveEntity(pocketlistsEventInterface $event)
    {
        $event->getName();
    }

    /**
     * @return pocketlistsProPluginAutomationService
     */
    public function getAutomationService()
    {
        if ($this->automation === null) {
            $this->automation = new pocketlistsProPluginAutomationService();
        }

        return $this->automation;
    }

    /**
     * @return pocketlistsProPluginCronManager
     */
    public function getCronManager()
    {
        if ($this->cronManager === null) {
            $this->cronManager = new pocketlistsProPluginCronManager();
        }

        return $this->cronManager;
    }
}
