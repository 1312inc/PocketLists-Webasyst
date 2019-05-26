<?php

/**
 * Class pocketlistsItemIcon
 */
class pocketlistsItemIcon
{
    /**
     * @param int $priority
     *
     * @return string
     */
    public function getIconByItemPriority($priority = 0)
    {
        switch ($priority) {
            case pocketlistsItem::PRIORITY_BURNINHELL:
                return '🔥';

            case pocketlistsItem::PRIORITY_BLACK:
                return '⚫';

            case pocketlistsItem::PRIORITY_RED:
                return '❗';

            case pocketlistsItem::PRIORITY_YELLOW:
            case pocketlistsItem::PRIORITY_GREEN:
            case pocketlistsItem::PRIORITY_NORM:
            default:
                return '&#9898;';
        }
    }
}
