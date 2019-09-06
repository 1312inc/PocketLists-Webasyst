<?php

/**
 * Class pocketlistsProPluginLogItem
 */
class pocketlistsProPluginLogItem extends pocketlistsProPluginLogAbstract
{
    const ITEM_ACTION_ADD_LABEL = 'add_label';

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
     */
    public function getLabel($smth)
    {
        $params = $this->log->getParams();

        return ifset($params, 'label', $smth, 'no saved label ' . $smth);
    }

    /**
     * @return string
     * @throws waException
     */
    public function getActionExplained()
    {
        switch ($this->log->getAction()) {
            case pocketlistsLog::ACTION_ADD:
                return sprintf_wp('%s created the to-do', $this->getActorName());

            case pocketlistsLog::ACTION_COMPLETE:
                return sprintf_wp('%s completed the to-do', $this->getActorName());

            case pocketlistsLog::ACTION_DELETE:
                return sprintf_wp('%s deleted the to-do', $this->getActorName());

            case pocketlistsLog::ACTION_UPDATE:
                $itemAction = $this->getItemAction();
                switch ($itemAction) {
                    case pocketlistsLog::ITEM_ACTION_NEW_ASSIGN:
                        $assignedName = $this->log->getAssignContact()->getName();

                        return sprintf_wp(
                            '%s assign to-do to %s',
                            $this->getActorName(),
                            $assignedName
                        );

                    case self::ITEM_ACTION_ADD_LABEL:
                        return sprintf_wp(
                            '%s added <span style="background: #%s">%s</span> label',
                            $this->getActorName(),
                            $this->getLabel('color'),
                            htmlspecialchars($this->getLabel('name'), ENT_QUOTES)
                        );

                    default:
                        return sprintf_wp('%s edited the to-do', $this->getActorName());
                }

            default:
                return sprintf_wp('%s updated the to-do', $this->getActorName());
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
