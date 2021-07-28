<?php

final class pocketlistsTasksBackendTasks
{
    /**
     * @param $params
     *
     * @return null
     */
    public function execute(&$params)
    {
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

            $return = [];

            $view = new waSmarty3View(wa());

            /** @var tasksTask $task */
            foreach ($params['tasks'] as $task) {
                $undoneItemsCount = pl2()->getModel(pocketlistsItemLink::class)
                    ->countUndoneLinkedItems(
                        pocketlistsAppLinkTasks::APP,
                        pocketlistsAppLinkTasks::TYPE_TASK,
                        $task->id
                    );

                $viewParams = array_merge(
                    [
                        'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
                        'app' => $app,
                        'task_url' => sprintf(
                            '%stasks/#/task/%d.%d/',
                            wa()->getConfig()->getBackendUrl(true),
                            $task->project_id,
                            $task->number
                        ),
                        'plurl' => wa()->getAppUrl(pocketlistsHelper::APP_ID),
                        'user' => pl2()->getUser(),
                        'count_undone_items' => $undoneItemsCount,
                        'externalApp' => 'tasks',
                    ],
                    pl2()->getDefaultViewVars()
                );

                $hook = $task->attachments ? 'after_attachments' : 'after_description';

                $template = wa()->getAppPath(
                    sprintf(
                        'templates/include%s/app_hook/tasks.backend_tasks.%s.html',
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
                            ]
                        );

                        $return[$task->id] = [
                            $hook => $view->fetch($template),
                        ];
                    } catch (Exception $ex) {
                        waLog::log(sprintf('%s error %s', $hook, $ex->getMessage()), 'pocketlists/tasks.log');
                    }
                }
            }

            if ($return) {
                return ['tasks' => $return];
            }
        } catch (Exception $ex) {
        }

        return null;
    }
}
