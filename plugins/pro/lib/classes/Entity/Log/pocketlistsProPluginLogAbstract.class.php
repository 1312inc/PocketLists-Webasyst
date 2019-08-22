<?php

/**
 * Class pocketlistsProPluginLogAbstract
 */
abstract class pocketlistsProPluginLogAbstract
{
    /**
     * @var pocketlistsLog
     */
    protected $log;

    /**
     * @var pocketlistsContact
     */
    protected $actor;

    /**
     * pocketlistsProPluginLog constructor.
     *
     * @param pocketlistsLog $log
     */
    public function __construct(pocketlistsLog $log)
    {
        $this->log = $log;
    }

    /**
     * @return pocketlistsLog
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @return string
     */
    abstract public function getLogEntry();

    /**
     * @return string
     */
    abstract public function getActionExplained();

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->log->getAction() === pocketlistsLog::ACTION_DELETE ? 'delete' : '';
    }

    /**
     * @return string
     */
    public function getGlyph()
    {
        return $this->log->getAction();
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws pocketlistsLogicException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->log, $name)) {
            return $this->log->{$name}($arguments);
        }

        throw new pocketlistsLogicException(sprintf_wp('Unknown log method %s', $name));
    }

    /**
     * @return pocketlistsContact
     * @throws waException
     */
    public function getActor()
    {
        if ($this->actor === null) {
            $this->actor = pl2()->getEntityFactory(pocketlistsContact::class)
                ->createNewWithId($this->log->getContactId());
        }

        return $this->actor;
    }
}
