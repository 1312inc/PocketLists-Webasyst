<?php

/**
 * Class pocketlistsProPluginAbstractHookHandler
 */
abstract class pocketlistsProPluginAbstractHookHandler
{
    /**
     * @var pocketlistsProPlugin
     */
    protected $plugin;

    /**
     * pocketlistsProPluginHandlerAbstractHook constructor.
     */
    public function __construct()
    {
        $this->plugin = pocketlistsProPlugin::getInstance();
    }

    /**
     * @param null|mixed $params
     *
     * @return mixed
     */
    abstract public function handle($params = null);

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getViewTemplate($name)
    {
        return sprintf('%s/templates/hooks/%s.html', $this->plugin->getPath(), $name);
    }

    /**
     * @return waView
     */
    protected function getView()
    {
        return $this->plugin->getView();
    }
}
