<?php

class pocketlistsListUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $list_id = ifset($_json, 'id', 0);
        $name = ifset($_json, 'name', '');
        $icon = ifset($_json, 'icon', pocketlistsList::DEFAULT_ICON);
        $color = ifset($_json, 'color', pocketlistsStoreColor::NONE);
        $sort = ifset($_json, 'sort', 0);
        $rank = ifset($_json, 'rank', '');

        /** @var pocketlistsList $list */
        if (!is_numeric($list_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'id'), 400);
        } elseif (!is_string($name)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'name'), 400);
        } elseif (!is_string($icon)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'icon'), 400);
        } elseif (!is_string($color)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'color'), 400);
        } elseif (!array_key_exists($color, pocketlistsStoreColor::getColors())) {
            throw new waAPIException('type_error', _w('Unknown value color'), 400);
        } elseif (!is_numeric($sort)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'sort'), 400);
        } elseif (!is_string($rank)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'rank'), 400);
        } elseif (empty($list_id) || $list_id < 1 || !$list = pl2()->getEntityFactory(pocketlistsList::class)->findById($list_id)) {
            throw new waAPIException('not_found', _w('List not found'), 404);
        }

        if ($name) {
            $list->setName($name);
        }
        if ($color) {
            $list->setColor($color);
        }
        $list->setIcon($icon)
            ->setSort($sort)
            ->setRank($rank)
            ->setUpdateDatetime(date('Y-m-d H:i:s'))
            ->setContact($this->getUser());
        if (pl2()->getEntityFactory(pocketlistsList::class)->save($list)) {
            $this->response = [
                'id'                  => $list->getId(),
                'contact_id'          => $list->getContactId(),
                'parent_id'           => $list->getParentId(),
                'sort'                => $list->getSort(),
                'rank'                => $list->getRank(),
                'has_children'        => $list->isHasChildren(),
                'status'              => $list->getStatus(),
                'priority'            => $list->getPriority(),
                'calc_priority'       => $list->getCalcPriority(),
                'create_datetime'     => $list->getCreateDatetime(),
                'update_datetime'     => $list->getUpdateDatetime(),
                'complete_datetime'   => $list->getCompleteDatetime(),
                'complete_contact_id' => $list->getCompleteContactId(),
                'name'                => $list->getName(),
                'due_date'            => $list->getDueDate(),
                'due_datetime'        => $list->getDueDatetime(),
                'location_id'         => $list->getLocationId(),
                'amount'              => $list->getAmount(),
                'currency_iso3'       => $list->getCurrencyIso3(),
                'assigned_contact_id' => $list->getAssignedContactId(),
                'repeat'              => $list->getRepeat(),
                'uuid'                => $list->getUuid(),
                'pocket_id'           => $list->getPocketId(),
                'type'                => $list->getType(),
                'icon'                => $list->getIcon(),
                'archived'            => $list->isArchived(),
                'hash'                => $list->getHash(),
                'color'               => $list->getColor(),
                'passcode'            => $list->getPasscode(),
                'key_item_id'         => $list->getKeyItemId(),
            ];
        } else {
            throw new waAPIException('save_error', _w('Error while saving item'));
        }
    }
}
