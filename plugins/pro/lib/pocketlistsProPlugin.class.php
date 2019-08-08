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
        return $this->getView()->fetch($this->getViewTemplate('backend_head'));
    }

    /**
     * @return array
     */
    public function backendSettingsHandler()
    {
        $sidebarLi = $this->getView()->fetch($this->getViewTemplate('backend_settings.sidebar_li'));

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
            $return[$hook] = $this->getView()->fetch($this->getViewTemplate('backend_sidebar.'.$hook));
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
            $return[$hook] = $this->getView()->fetch($this->getViewTemplate('backend_pocket.'.$hook));
        }

        return $return;
    }

    /**
     * @param null|pocketlistsItem $item
     *
     * @return array
     * @throws waException
     */
    public function backendItemAddHandler($item)
    {
        $return = [];

        /** @var pocketlistsProPluginLabelFactory $factoryLabel */
        $factoryLabel = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
        /** @var pocketlistsProPluginShortcutFactory $factoryShortcut */
        $factoryShortcut = pl2()->getEntityFactory(pocketlistsProPluginShortcut::class);

        $label = null;
        if ($item instanceof pocketlistsItem) {
            $label = $factoryLabel->findForItem($item);
        }
        $label = $label ?: new pocketlistsProPluginLabel();

        $this->getView()->assign(
            [
                'itemLabel' => $label,
                'labels'    => $factoryLabel->findAll(),
                'shortcutsGrouped' => $factoryShortcut->findAllGrouped(),
            ]
        );

        $return['compact'] = $this->getView()->fetch($this->getViewTemplate('backend_item_add'));
        $return['detail'] = $return['compact'];

        return $return;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getViewTemplate($name)
    {
        return sprintf('%s/templates/hooks/%s.html', $this->path, $name);
    }

    public function saveEntity(pocketlistsEventInterface $event)
    {
        $event->getName();
    }
}
