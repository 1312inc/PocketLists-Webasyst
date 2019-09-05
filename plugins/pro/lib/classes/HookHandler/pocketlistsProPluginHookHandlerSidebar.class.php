<?php

/**
 * Class pocketlistsProPluginHookHandlerSidebar
 */
class pocketlistsProPluginHookHandlerSidebar extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|mixed $params
     *
     * @return mixed
     * @throws waException
     */
    public function handle($params = null)
    {
        $return = [];

        $this->getView()->assign('labelsCount', pl2()->getModel(pocketlistsProPluginLabel::class)->countAll());

        $hooks = ['streams_li', 'views_li', 'section_block', 'system_li'];
        foreach ($hooks as $hook) {
            $return[$hook] = $this->getView()->fetch($this->getViewTemplate('backend_sidebar.'.$hook));
        }

        return $return;
    }
}
