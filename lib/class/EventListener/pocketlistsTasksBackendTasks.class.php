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

            $return = [];
            /** @var tasksTask $task */
            foreach ($params['tasks'] as $task) {
                $return[$task->id] = [
                    'before_buttons' => sprintf('<br><b>%d: "before_buttons" !!! hello from pl2 </b><br>', $task->id),
                    'after_description' => sprintf('<br><b>%d: "after_description" !!! hello from pl2 </b><br>', $task->id),
                    'after_attachments' => sprintf('<br><b>%d: "after_attachments" !!! hello from pl2 </b><br>', $task->id),
                ];
            }

            if ($return) {
                return ['tasks' => $return];
            }
        } catch (Exception $ex) {
        }

        return null;
    }
}
