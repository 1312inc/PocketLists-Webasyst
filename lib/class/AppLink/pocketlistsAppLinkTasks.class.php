<?php


final class pocketlistsAppLinkTasks extends pocketlistsAppLinkAbstract implements pocketlistsAppLinkInterface
{
    const APP       = 'tasks';
    const TYPE_TASK = 'task';

    /**
     * @var tasksTaskModel
     */
    private $taskModel;

    public function __construct()
    {
        parent::__construct();

        if ($this->enabled) {
            $this->taskModel = new tasksTaskModel();
        }
    }

    public function getApp()
    {
        return self::APP;
    }

    public function getTypes()
    {
        return [self::TYPE_TASK];
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     * @throws waException
     */
    public function getLinkUrl(pocketlistsItemLink $itemLink)
    {
        $data = $itemLink->getAppEntity();
        if (isset($data['project_id'], $data['number'])) {
            return sprintf('%s#/task/%d.%d/', wa()->getAppUrl($this->getApp()), $data['project_id'], $data['number']);
        }

        return '';
    }

    public function getEntityNum(pocketlistsItemLink $itemLink)
    {
        return '';
    }

    public function getAppEntity(pocketlistsItemLink $itemLink)
    {
        try {
            return pl2()->getEntityRepository('pocketlistsAppLinkTasks')->getTask($itemLink->getEntityId());
        } catch (waException $ex) {
            return false;
        }
    }

    public function getExtraData(pocketlistsItemLink $itemLink)
    {
        $task = $itemLink->getAppEntity();
        $status = $task->getStatus();
        $assignContact = $task->getAssignedContact();

        return [
            'task' => $itemLink->getAppEntity(),
            'link' => $this->getLinkUrl($itemLink),
            'status' => $status,
            'assign_username' => $assignContact
                ? $assignContact->getName()
                : _wd(pocketlistsHelper::APP_ID, '(not assigned)'),
            'update_datetime' => $task->update_datetime,
        ];
    }

    public function getLinkRegexs()
    {
        return [
            self::TYPE_TASK => [
                '.*/task/(\d+)\.(\d+).*',
            ],
        ];
    }

    /**
     * @param array  $regex
     * @param string $type
     *
     * @return int|null
     */
    public function getEntityIdByLinkRegexs($regex, $type)
    {
        switch ($type) {
            case self::TYPE_TASK:
                $task = $this->taskModel->getByField(['project_id' => $regex[1], 'number' => $regex[2]]);

                return $task ? (int) $task['id'] : null;

            default:
                return null;
        }
    }

    /**
     * @param pocketlistsContact|null $user
     * @param string|null             $accessTo
     *
     * @return bool
     * @throws waException
     */
    public function userCanAccess(pocketlistsContact $user = null, $accessTo = null)
    {
        if (!$this->enabled) {
            return false;
        }

        if ($user === null) {
            $user = wa(pocketlistsHelper::APP_ID)->getConfig()->getUser();
        }

        $right = $user->getContact()->getRights($this->getApp());

        switch ($accessTo) {
            case 'sidebar':
                return isset($right['backend']) && $right['backend'] > 1;

            default:
                return !empty($right);
        }
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderAutocomplete(pocketlistsItemLink $itemLink)
    {
        $data = $itemLink->getDataArray();

        return sprintf('<span>%s %s %s</span>', _w('Task'), $data['identifier'], $data['name']);
    }

    /**
     * @param       $term
     * @param array $params
     * @param       $count
     *
     * @return array
     * @throws waException
     */
    protected function autocompleteTask($term, $params = [], $count)
    {
        $result = [];

        $tasksModel = new tasksTaskModel();

        $tasks = [];
        $i = 0;
        $tasksRights = new tasksRights();
        do {
            $foundTasks = $tasksModel->getAutocomplete($term, $count, true, $count * $i++);
            if (!$foundTasks) {
                break;
            }

            $tasksRights->extendTasksByRightsInfo($foundTasks, wa()->getUser()->getId());
            $tasks += array_filter(
                $foundTasks,
                static function (array $task) {
                    return $task['rights_info']['can_view'] === true;
                }
            );
        } while (count($tasks) < $count);

        /** @var pocketlistsItemLinkFactory $itemlinkFactory */
        $itemlinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);

        foreach ($tasks as $task) {
            /** @var pocketlistsItemLink $linkEntity */
            $linkEntity = $itemlinkFactory->createNew();

            $linkEntity
                ->setApp($this->getApp())
                ->setEntityType(self::TYPE_TASK)
                ->setEntityId($task['id'])
                ->setDataArray(
                    [
                        'name' => $task['name'],
                        'identifier' => sprintf('%d.%d', $task['project_id'], $task['number']),
                    ]
                );

            $result[] = [
                'label' => $this->renderAutocomplete($linkEntity),
                'value' => sprintf('%d.%d', $task['project_id'], $task['number']),
                'data' => [
                    'model' => pl2()->getHydrator()->extract($linkEntity, [], $itemlinkFactory->getDbFields()),
                    'preview' => $this->renderPreview($linkEntity),
                ],
            ];
        }

        return $result;
    }

    public function countItemsForApp(array $params)
    {
        return 0;
    }

    public function getItemsForApp(array $params)
    {
        return [];
    }
}