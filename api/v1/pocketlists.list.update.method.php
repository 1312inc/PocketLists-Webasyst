<?php

class pocketlistsListUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $list_id = (int) ifset($_json, 'id', 0);
        $name = (string) ifset($_json, 'name', '');
        $icon = (string) ifset($_json, 'icon', pocketlistsList::DEFAULT_ICON);
        $color = (string) ifset($_json, 'color', '');

        /** @var pocketlistsList $list */
        if (empty($list_id) || $list_id < 1 || !$list = pl2()->getEntityFactory(pocketlistsList::class)->findById($list_id)) {
            throw new waAPIException('not_found', _w('List not found'), 404);
        }

        if ($name) {
            $list->setName($name);
        }
        if ($color) {
            $list->setColor($color);
        }
        $list->setIcon($icon)
            ->setUpdateDatetime(date('Y-m-d H:i:s'))
            ->setContact($this->getUser());
        if (pl2()->getEntityFactory(pocketlistsList::class)->save($list)) {
            $this->response = [
                'pocket_id'           => $list->getPocketId(),
                'type'                => $list->getType(),
                'icon'                => $list->getIcon(),
                'archived'            => $list->isArchived(),
                'hash'                => $list->getHash(),
                'color'               => $list->getColor(),
                'passcode'            => $list->getPasscode(),
                'key_item_id'         => $list->getKeyItemId(),
                'items_count'         => $list->getItemsCount(),
                'max_priority'        => $list->getMaxPriority(),
                'min_due_date'        => $list->getMinDueDate(),
                'min_due_datetime'    => $list->getMinDueDatetime(),
                'id'                  => $list->getId(),
                'contact_id'          => $list->getContactId(),
                'sort'                => $list->getSort(),
                'create_datetime'     => $list->getCreateDatetime(),
                'update_datetime'     => $list->getUpdateDatetime(),
                'complete_datetime'   => $list->getCompleteDatetime(),
                'complete_contact_id' => $list->getCompleteContactId(),
                'name'                => $list->getName(),
                'status'              => $list->getStatus(),
                'has_children'        => $list->isHasChildren(),
                'priority'            => $list->getPriority(),
                'calc_priority'       => $list->getCalcPriority(),
                'parent_id'           => $list->getParentId(),
                'list_id'             => $list->getListId(),
                'note'                => $list->getNote(),
                'due_date'            => $list->getDueDate(),
                'due_datetime'        => $list->getDueDatetime(),
                'location_id'         => $list->getLocationId(),
                'amount'              => $list->getAmount(),
                'currency_iso3'       => $list->getCurrencyIso3(),
                'assigned_contact_id' => $list->getAssignedContactId(),

                'repeat'              => $list->getRepeat(),
                'key_list_id'         => $list->getKeyListId(),
                'favorite'            => $list->isFavorite(),
            ];
        } else {
            throw new waAPIException('save_error', _w('Error while saving item'));
        }
    }
}
