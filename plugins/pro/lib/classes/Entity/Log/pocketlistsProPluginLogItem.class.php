<?php

/**
 * Class pocketlistsProPluginLogItem
 */
class pocketlistsProPluginLogItem extends pocketlistsProPluginLogAbstract
{
    /**
     * @return string
     */
    public function getLogEntry()
    {
        $params = $this->log->getParams();

        return ifset($params, 'item', 'name', 'no saved item name');
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        $name = $this->log->getContact()->getName();
        $assignedName = $this->log->getAssignContact()->getName();

        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s created the to-do', $name);

            case pocketlistsLog::ACTION_COMPLETE:
                return sprintf_wp('%s completed the to-do', $name);

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the to-do', $name);

            case pocketlistsLog::ACTION_UPDATE:
                $itemAction = $this->getItemAction();
                switch ($itemAction) {
                    case 'new assign':
                        return sprintf_wp(
                            '%s assign to-do to %s',
                            $name,
                            $assignedName
                        );

                    default:
                        return sprintf_wp('%s edited the to-do', $name);
                }

            default:
                return sprintf_wp('%s updated the to-do', $name);
        }
    }

    /**
     * @return string
     */
    private function getItemAction()
    {
        $params = $this->log->getParams();

        return  ifset($params, 'item_action', '');
    }
}
