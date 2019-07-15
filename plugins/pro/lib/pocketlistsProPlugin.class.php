<?php

/**
 * Class pocketlistsProPlugin
 */
class pocketlistsProPlugin extends waPlugin
{
    /**
     * @var waView
     */
    protected $view;

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

    /**
     * @return string
     */
    public function backendHeadHandler()
    {
        return $this->getView()->fetch($this->getViewTemplate('backend_head.html'));
    }

    /**
     * @return array
     */
    public function backendSettingsHandler()
    {
        $sidebarLi = $this->getView()->fetch($this->getViewTemplate('backend_settings.sidebar_li.html'));

        return [
            'sidebar_li' => $sidebarLi,
        ];
    }

    /**
     * @return array
     */
    public function backendSidebarHandler()
    {
        $return = [];

        $hooks = ['streams_li', 'views_li', 'section_block', 'system_li'];
        foreach ($hooks as $hook) {
            $return[$hook] = $this->getView()->fetch($this->getViewTemplate('backend_sidebar.'.$hook.'.html'));
        }

        return $return;
    }

    /**
     * @return array
     */
    public function backendPocketHandler()
    {
        $return = [];

        $hooks = ['sidebar_section'];
        foreach ($hooks as $hook) {
            $return[$hook] = $this->getView()->fetch($this->getViewTemplate('backend_pocket.'.$hook.'.html'));
        }

        return $return;
    }

    /**
     * @return array
     */
    public function backendItemAddHandler()
    {
        $return = [];

        $hooks = ['compact'];
        foreach ($hooks as $hook) {
            $return[$hook] = $this->getView()->fetch($this->getViewTemplate('backend_item_add.'.$hook.'.html'));
        }

        return $return;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getViewTemplate($name)
    {
        return sprintf('%s/templates/hooks/%s', $this->path, $name);
    }
}
