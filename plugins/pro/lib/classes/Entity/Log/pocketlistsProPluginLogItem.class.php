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
        return $this->log->getParamValueByKey('item.name', 'no saved item name');
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
                $itemAction = $this->log->getParamValueByKey('item_action');
                switch ($itemAction) {
                    case pocketlistsLog::ITEM_ACTION_NEW_ASSIGN:
                        $assignedName = $this->log->getAssignContact()->getName();

                        return sprintf_wp(
                            '%s assigned to-do to %s',
                            $this->getActorName(),
                            $assignedName
                        );

                    case self::ITEM_ACTION_ADD_LABEL:
                        return sprintf_wp(
                            '%s updated the label to <span class="pl-label" style="background: #%s">%s</span>',
                            $this->getActorName(),
                            $this->log->getParamValueByKey('item.color'),
                            htmlspecialchars($this->log->getParamValueByKey('item.name'), ENT_QUOTES)
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
    public function getMoreHtml()
    {
        $_entity = $this->log->getParamValueByKey('item.shop', []);
        return $_entity ? '<a href="'.$_entity['link'].'">' . $_entity['app_icon'] . $_entity['entity_num'] . '</a>' : '';
    }
}
