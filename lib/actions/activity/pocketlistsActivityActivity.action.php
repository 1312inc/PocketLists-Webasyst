<?php

/**
 * Class pocketlistsActivityActivityAction
 */
class pocketlistsActivityActivityAction extends pocketlistsViewAction
{
    const LIMIT = 30;

    /**
     * @var bool
     */
    protected $lazy = true;

    /**
     * @var string
     */
    protected $type = 'Activity';

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var int
     */
    protected $entity_id;

    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $this->offset = $this->getParam('offset', 0, waRequest::TYPE_INT);
        $this->entity_id = $this->getParam('entity_id', 0, waRequest::TYPE_INT);
        if ($this->type !== 'Activity') {
            pocketlistsAssert::gt($this->entity_id, 0);
        }

        $activityLogs = [];
        foreach ($this->getLogs() as $log) {
            $activityLogs[] = pocketlistsLogFactory::createFromLog($log);
        }

        $this->setTemplate(
            wa()->getAppPath(
                sprintf('templates/actions%s/activity/Activity.html', pl2()->getUI2TemplatePath())
            )
        );

        $this->view->assign(
            [
                'logs'      => $activityLogs,
                'lazy'      => $this->lazy,
                'type'      => $this->type,
                'entity_id' => $this->entity_id,
            ]
        );
    }

    /**
     * @param string $name
     * @param mixed  $default
     * @param string $type
     *
     * @return mixed|null
     */
    protected function getParam($name, $default, $type)
    {
        return ifset($this->params, $name, waRequest::request($name, $default, $type));
    }

    /**
     * @return pocketlistsLog[]
     * @throws waDbException
     * @throws waException
     */
    protected function getLogs()
    {
        if (!pocketlistsRBAC::canAssign()) {
            throw new pocketlistsForbiddenException();
        }

        /** @var pocketlistsLogFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsLog::class);

        return $factory
            ->setOffset($this->offset * self::LIMIT)
            ->setLimit(self::LIMIT)
            ->findLastAll(true, $this->type === 'Activity');
    }
}
