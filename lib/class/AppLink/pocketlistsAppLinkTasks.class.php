<?php


final class pocketlistsAppLinkTasks extends pocketlistsAppLinkAbstract implements pocketlistsAppLinkInterface
{
    const APP       = 'tasks';
    const TYPE_TASK = 'task';

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
        }    }

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
        return [];
    }

    public function userCanAccess(pocketlistsContact $user = null)
    {
        return true;
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
        $tasks = $tasksModel->getAutocomplete($term, $count, true);

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
}