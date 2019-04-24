<?php

/**
 * Class pocketlistsBaseNotification
 */
class pocketlistsBaseNotification
{
    /**
     * @var pocketlistsList[]
     */
    private static $lists = [];

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsList|pocketlistsNullList
     * @throws waException
     */
    protected function getList($item)
    {
        if (!isset(self::$lists[$item->getListId()])) {
            if ($item->getListId()) {
                self::$lists[$item->getListId()] = $item->getList();
            } else {
                self::$lists[$item->getListId()] = (new pocketlistsNullList())->setName(_w('Stream'));
            }
        }

        return self::$lists[$item->getListId()];
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return bool
     */
    protected function canSend(pocketlistsContact $contact)
    {
        return $contact->isExists() && !$contact->isMe();
    }
}
