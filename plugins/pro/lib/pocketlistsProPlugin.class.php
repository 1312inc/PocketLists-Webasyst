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
     * @var pocketlistsProPlugin
     */
    protected static $instance;

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
}
