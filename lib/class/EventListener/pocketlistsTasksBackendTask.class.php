<?php

final class pocketlistsTasksBackendTask
{
    /**
     * @param $params
     *
     * @return null
     */
    public function execute(&$params)
    {
        if (!isset($params['task'])) {
            return null;
        }

        /** @var tasksTask $task */
        $task = $params['task'];

        try {
            if (!wa()->getUser()->getRights('pocketlists')) {
                return null;
            }

            $app = pl2()->getLinkedApp(pocketlistsAppLinkTasks::APP);

            if (!$app->isEnabled()) {
                return null;
            }

            if (!$app->userCanAccess()) {
                return null;
            }

            $view = new waSmarty3View(wa());

            wa(pocketlistsHelper::APP_ID, true);
            $itemAdd = (new pocketlistsItemAddAction(['external' => true, 'externalApp' => 'tasks']))
                ->display(false);
            wa(pocketlistsAppLinkTasks::APP, true);

            $hasItems = pl2()->getModel(pocketlistsItemLink::class)->countLinkedItems(
                pocketlistsAppLinkTasks::APP,
                pocketlistsAppLinkTasks::TYPE_TASK,
                $task->id
            );

            $viewParams = array_merge(
                [
                    'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
                    'app' => $app,
                    'task' => $task,
                    'plurl' => wa()->getAppUrl(pocketlistsHelper::APP_ID),
                    'items_undone' => [],
                    'items_done' => [],
                    'count_done_items' => 0,
                    'count_undone_items' => 0,
                    'fileupload' => 1,
                    'user' => pl2()->getUser(),
                    'itemAdd' => $itemAdd,
                    'externalApp' => 'tasks',
                ],
                pl2()->getDefaultViewVars()
            );

            if ($hasItems) {
                $items = pl2()
                    ->getEntityFactory(pocketlistsItem::class)
                    ->findAllForApp($app, pocketlistsAppLinkTasks::TYPE_TASK, $task->id);

                $filter = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone();

                if ($items) {
                    $viewParams['items_undone'] = $filter->getProperSortUndone();
                    $viewParams['count_undone_items'] = $filter->countUndone();
                    $viewParams['items_done'] = $filter->getItemsDone();
                    $viewParams['count_done_items'] = $filter->countDone();
                }
            }

            $return = [];

            $hook = $task->attachments ? 'after_attachments' : 'after_description';

            $template = wa()->getAppPath(
                sprintf(
                    'templates/include%s/app_hook/tasks.backend_task.%s.html',
                    pl2()->getUI2TemplatePath(null, 'tasks'),
                    $hook
                ),
                pocketlistsHelper::APP_ID
            );

            if (file_exists($template)) {
                try {
                    $view->assign(
                        [
                            'params' => $viewParams,
                            'pl2' => pl2(),
                            'pl2_attachments_path' => wa()->getDataUrl('attachments', true, pocketlistsHelper::APP_ID),
                        ]
                    );
                    $return[$hook] = $view->fetch($template);
                } catch (Exception $ex) {
                    waLog::log(sprintf('%s error %s', $hook, $ex->getMessage()), 'pocketlists/tasks.log');
                }
            }

            return $return;
        } catch (Exception $ex) {
        }

        return null;
    }
}
