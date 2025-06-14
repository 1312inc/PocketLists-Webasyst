<?php

/**
 * Class pocketlistsActivityAction
 */
class pocketlistsActivityAction extends pocketlistsViewAction
{
    const LIMIT = 30;

    /**
     * @var bool
     */
    protected $lazy = true;

    /**
     * @var string
     */
    protected $type = '';

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
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl(null, true));
            }
        }
        $this->offset = $this->getParam('offset', 0, waRequest::TYPE_INT);
        $this->entity_id = $this->getParam('entity_id', 0, waRequest::TYPE_INT);

        $activityLogs = [];
        foreach ($this->getLogs() as $log) {
            $activityLogs[] = pocketlistsLogFactory::createFromLog($log);
        }

        if (wa()->whichUI() !== '1.3') {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        $this->setTemplate(wa()->getAppPath(sprintf('templates/actions%s/activity/Activity.html', pl2()->getUI2TemplatePath())));

        $this->view->assign([
            'logs'      => $activityLogs,
            'lazy'      => $this->lazy,
            'type'      => $this->type,
            'entity_id' => $this->entity_id,
        ]);
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
        /** @var pocketlistsLogFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsLog::class);

        return $factory
            ->setOffset($this->offset * self::LIMIT)
            ->setLimit(self::LIMIT)
            ->findLastAll(true, true);
    }
}
