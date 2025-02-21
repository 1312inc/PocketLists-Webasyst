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
     * @throws waException
     */
    public function getActorName()
    {
        return htmlspecialchars($this->log->getContact()->getName(), ENT_QUOTES);
    }

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
     * @return string
     */
    public function getMoreHtml()
    {
        return '';
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
}
