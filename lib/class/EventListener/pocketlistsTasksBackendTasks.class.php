<?php

final class pocketlistsTasksBackendTasks
{
    public static $tasks_data = [];

    protected function ensureTasksData($tasks)
    {
        $missing_ids = array_keys(array_diff_key($tasks, self::$tasks_data));
        if (!$missing_ids) {
            return;
        }

        foreach($missing_ids as $task_id) {
            self::$tasks_data[$task_id] = [
                'task' => $tasks[$task_id],
                'undone_count' => 0,
            ];
        }

        $rows = pl2()->getModel(pocketlistsItemLink::class)->countLinkedItemsByAppAndEntities(
            pocketlistsAppLinkTasks::APP,
            pocketlistsAppLinkTasks::TYPE_TASK,
            $missing_ids
        );
        foreach($rows as $row) {
            self::$tasks_data[$row['entity_id']]['undone_count'] = $row['count_entities'];
        }
    }

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

            $this->ensureTasksData($params['tasks']);

            if (wa('tasks')->whichUI() === '2.0') {
                // no need to do anything else in modern version of Tasks that only supports ui 2.0
                return;
            }

            $default_vars = [
                'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
                'app' => $app,
                'plurl' => wa()->getAppUrl(pocketlistsHelper::APP_ID),
                'user' => pl2()->getUser(),
                'externalApp' => 'tasks',
            ] + pl2()->getDefaultViewVars();

            $return = [];
            $view = new waSmarty3View(wa());

            /** @var tasksTask $task */
            foreach ($params['tasks'] as $task) {
                $viewParams = [
                    'task_url' => sprintf(
                        '%stasks/#/task/%d.%d/',
                        wa()->getConfig()->getBackendUrl(true),
                        $task->project_id,
                        $task->number
                    ),
                    'count_undone_items' => ifset(self::$tasks_data, $task->id, 'undone_count', 0),
                ] + $default_vars;

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
                        waLog::log(sprintf('backend_tasks render error %s\n%s', $ex->getMessage(), $ex->getTraceAsString()), 'pocketlists/tasks.log');
                    }
                }
            }

            if ($return) {
                return ['tasks' => $return];
            }
        } catch (Exception $ex) {
            waLog::log(sprintf('backend_tasks error %s\n%s', $ex->getMessage(), $ex->getTraceAsString()), 'pocketlists/tasks.log');
        }

        return null;
    }

    /**
     *
     */
    public function controllerAfterHook(&$params, $event_name) {
        $template = wa()->getAppPath(
            sprintf(
                'templates/include%s/app_hook/tasks.backend_tasks.controller_after.html',
                pl2()->getUI2TemplatePath(null, 'tasks')
            ),
            pocketlistsHelper::APP_ID
        );

        if (!self::$tasks_data || !file_exists($template)) {
            return;
        }

        $task_counts = [];
        foreach(self::$tasks_data as $task_id => $data) {
            $task_counts[$task_id] = $data['undone_count'];
        }

        try {
            $view =  wa('tasks')->getView();
            $view->assign([
                'task_counts' => $task_counts,
                'params' => [
                    'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
                    'app' => pl2()->getLinkedApp(pocketlistsAppLinkTasks::APP),
                    'plurl' => wa()->getAppUrl(pocketlistsHelper::APP_ID),
                    'user' => pl2()->getUser(),
                    'externalApp' => 'tasks',
                ] + pl2()->getDefaultViewVars(),
            ]);
            echo $view->fetch($template);
        } catch (Exception $ex) {
            waLog::log(sprintf('controller_after error %s\n%s', $ex->getMessage(), $ex->getTraceAsString()), 'pocketlists/tasks.log');
        }

    }
}
