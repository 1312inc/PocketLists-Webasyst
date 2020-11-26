<?php

/**
 * Class pocketlistsAppLinkTasksRepository
 */
class pocketlistsAppLinkTasksRepository
{
    /**
     * @var tasksTask[]
     */
    private $tasks = [];

    /**
     * @param $taskId
     *
     * @return tasksTask
     */
    public function getTask($taskId)
    {
        if (!isset($this->tasks[$taskId])) {
            $this->tasks[$taskId] = new tasksTask($taskId);
        }

        return $this->tasks[$taskId];
    }
}
