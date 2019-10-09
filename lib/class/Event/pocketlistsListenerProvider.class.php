<?php

/**
 * Class pocketlistsListenerProvider
 */
class pocketlistsListenerProvider implements pocketlistsListenerProviderInterface
{
    const POCKETLISTS_EVENT_HANDLERS_KEY = 'pocketlists_event_handlers';
    const POCKETLISTS_EVENT_HANDLERS_TTL = 300;

    /**
     * @var array
     */
    protected $handlers;

    /**
     * pocketlistsListenerProvider constructor.
     */
    public function __construct()
    {
//        $this->handlers = pl2()->getCache()->get(self::POCKETLISTS_EVENT_HANDLERS_KEY);

        if (!is_array($this->handlers)) {
            $this->getAllHandlers();
            pl2()->getCache()->set(
                self::POCKETLISTS_EVENT_HANDLERS_KEY,
                $this->handlers,
                self::POCKETLISTS_EVENT_HANDLERS_TTL
            );
        }
    }

    /**
     * @param pocketlistsEventInterface $event
     *
     * @return iterable[callable]
     */
    public function getListenersForEvent(pocketlistsEventInterface $event)
    {
        return isset($this->handlers[$event->getName()]) ? $this->handlers[$event->getName()] : [];
    }

    /**
     * @param string $eventConfigFile
     */
    protected function addHandlersToEvent($eventConfigFile)
    {
        if (file_exists($eventConfigFile)) {
            $appEvents = require $eventConfigFile;
            foreach ($appEvents as $eventName => $eventHandler) {
                if (!isset($this->handlers[$eventName])) {
                    $this->handlers[$eventName] = [];
                }

                if (is_array($eventHandler[0])) {
                    $this->handlers[$eventName] += $eventHandler;
                } else {
                    $this->handlers[$eventName][] = $eventHandler;
                }
            }
        }
    }

    protected function getAllHandlers()
    {
        $this->addHandlersToEvent(wa()->getAppPath('lib/config/pl2_events.php', 'pocketlists'));

        $plugins = pl2()->getPlugins();
        foreach ($plugins as $pluginId => $plugin) {
            $this->addHandlersToEvent(wa()->getAppPath('plugins/'.$pluginId.'/lib/config/pl2_events.php', 'pocketlists'));
        }
    }
}
