<?php

/**
 * Class pocketlistsProPluginAbstractHookHandler
 */
abstract class pocketlistsProPluginAbstractHookHandler implements pocketlistsHookHandlerInterface
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
     * @param string      $name
     * @param string|null $app
     *
     * @return string
     */
    protected function getViewTemplate($name, $app = null)
    {
        return sprintf(
            '%s/templates/hooks%s/%s.html',
            $this->plugin->getPath(),
            pl2()->getUI2TemplatePath(null, $app),
            $name
        );
    }

    /**
     * @return waView
     */
    protected function getView()
    {
        return $this->plugin->getView();
    }
}
