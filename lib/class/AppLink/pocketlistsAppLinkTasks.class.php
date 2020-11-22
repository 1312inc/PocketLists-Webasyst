<?php


final class pocketlistsAppLinkTasks extends pocketlistsAppLinkAbstract
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
        $data = $itemLink->getApp();
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
        return [
            'task' => $itemLink->getAppEntity(),
            'link' => $this->getLinkUrl($itemLink),
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