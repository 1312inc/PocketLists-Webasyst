<?php

class pocketlistsItemUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $item_id = ifset($_json, 'id', 0);
        $list_id = ifset($_json, 'list_id', 0);
        $name = ifset($_json, 'name', '');
        $note = ifset($_json, 'note', '');
        $assigned_contact_id = ifset($_json, 'assigned_contact_id', 0);
        $priority = ifset($_json, 'priority', 0);
        $due_datetime = ifset($_json, 'due_datetime', '');
        $attachments = ifset($_json, 'attachments', null);

        /** @var pocketlistsItem $item */
        if (!is_numeric($item_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'item_id'), 400);
        } elseif (!is_numeric($list_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'list_id'), 400);
        } elseif (!is_numeric($assigned_contact_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'assigned_contact_id'), 400);
        } elseif (!is_numeric($priority)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'priority'), 400);
        } elseif (!is_string($name)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'name'), 400);
        } elseif (!is_string($note)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'note'), 400);
        } elseif (!is_string($due_datetime)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'due_datetime'), 400);
        } elseif (empty($item_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (empty($list_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'list_id'), 400);
        } elseif ($item_id < 1 || !$item = pl2()->getEntityFactory(pocketlistsItem::class)->findById($item_id)) {
            throw new waAPIException('not_found', _w('Item not found'), 404);
        } elseif ($list_id < 1 || !$list = pl2()->getEntityFactory(pocketlistsList::class)->findById($list_id)) {
            throw new waAPIException('not_found', _w('List not found'), 404);
        }

        if ($item->getListId()) {
            if (!pocketlistsRBAC::canAccessToList($item->getList())) {
                throw new waAPIException('forbidden', _w('Access denied'), 403);
            }
            if (
                $item->getAssignedContactId()
                && !pocketlistsRBAC::canAccessToList($item->getList(), $item->getAssignedContactId())
            ) {
                $item->setAssignedContactId();
            }
        }

        $match_note = pocketlistsNaturalInput::matchNote($name);
        if ($match_note) {
            if (empty($note)) {
                $name = $match_note['name'];
                $note = $match_note['note'];
            }
        }
        if ($assigned_contact_id) {
            $assign_contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($assigned_contact_id);
            if ($assign_contact->isExists()) {
                $item->setAssignedContact($assign_contact);
            } else {
                throw new waAPIException('not_found', _w('Assigned contact not found'), 404);
            }
        }
        if ($priority) {
            if (!in_array($priority, [1, 2, 3, 4, 5])) {
                throw new waAPIException('unknown_value', _w('Unknown priority'), 400);
            }
        } else {
            $match_priority = pocketlistsNaturalInput::matchPriority($name);
            if ($match_priority) {
                $name = $match_priority['name'];
                $priority = (int) $match_priority['priority'];
            }
        }
        if (!empty($due_datetime)) {
            $dt = date_create($due_datetime);
            if ($dt) {
                $item->setDueDate($dt->format('Y-m-d'))
                    ->setDueDatetime($dt->format('Y-m-d H:i:s'));
            } else {
                throw new waAPIException('unknown_value', _w('Unknown date'), 400);
            }
        }
        if (!is_null($attachments)) {
            if (empty($attachments)) {
                /** @var pocketlistsAttachmentFactory $attachment_factory */
                $attachment_factory = pl2()->getEntityFactory(pocketlistsAttachment::class);
                $attachment_factory->deleteAllByItem($item);
                $item->setAttachmentsCount(0);
            } else {
                $attachments = $this->updateFiles($item_id, $attachments);
                $item->setAttachmentsCount($item->getAttachmentsCount() + count(array_column($attachments, 'id')));
            }
        }

        $item->setName($name)
            ->setNote($note)
            ->setPriority($priority)
            ->setAssignedContactId($assigned_contact_id)
            ->setUpdateDatetime(date('Y-m-d H:i:s'));

        /** @var pocketlistsItemFactory $item_factory */
        $item_factory = pl2()->getEntityFactory(pocketlistsItem::class);
        $saved = $item_factory->save($item);
        if (!$saved) {
            throw new waAPIException('save_error', _w('Error while saving item'));
        }

        $result = $this->filterFields([[
            'id'                    => $item->getId(),
            'list_id'               => $item->getListId(),
            'contact_id'            => $item->getContactId(),
            'parent_id'             => $item->getParentId(),
            'sort'                  => $item->getSort(),
            'has_children'          => $item->isHasChildren(),
            'status'                => $item->getStatus(),
            'priority'              => $item->getPriority(),
            'calc_priority'         => $item->getCalcPriority(),
            'create_datetime'       => $item->getCreateDatetime(),
            'update_datetime'       => $item->getUpdateDatetime(),
            'complete_datetime'     => $item->getCompleteDatetime(),
            'complete_contact_id'   => $item->getCompleteContactId(),
            'name'                  => $item->getName(),
            'note'                  => $item->getNote(),
            'due_date'              => $item->getDueDate(),
            'due_datetime'          => $item->getDueDatetime(),
            'location_id'           => $item->getLocationId(),
            'amount'                => $item->getAmount(),
            'currency_iso3'         => $item->getCurrencyIso3(),
            'assigned_contact_id'   => $item->getAssignedContactId(),
            'repeat'                => $item->getRepeat(),
            'key_list_id'           => $item->getKeyListId(),
            'favorite'              => $item->isFavorite(),
            'attachments_count'     => $item->getAttachmentsCount(),
            'comments_count'        => $item->getCommentsCount(),
            'linked_entities_count' => $item->getLinkedEntitiesCount(),
            'uuid'                  => $item->getUuid(),
            'attachments'           => $attachments
        ]], [
            'id',
            'list_id',
            'contact_id',
            'parent_id',
            'sort',
            'has_children',
            'status',
            'priority',
            'calc_priority',
            'create_datetime',
            'update_datetime',
            'complete_datetime',
            'complete_contact_id',
            'name',
            'note',
            'due_date',
            'due_datetime',
            'location_id',
            'amount',
            'currency_iso3',
            'assigned_contact_id',
            'repeat',
            'key_list_id',
            'favorite',
            'attachments_count',
            'comments_count',
            'linked_entities_count',
            'uuid',
            'attachments'
        ], [
            'id' => 'int',
            'list_id' => 'int',
            'contact_id' => 'int',
            'parent_id' => 'int',
            'sort' => 'int',
            'has_children' => 'bool',
            'status' => 'int',
            'priority' => 'int',
            'calc_priority' => 'int',
            'create_datetime' => 'datetime',
            'update_datetime' => 'datetime',
            'complete_datetime' => 'datetime',
            'complete_contact_id' => 'int',
            'due_datetime' => 'datetime',
            'location_id' => 'int',
            'amount' => 'float',
            'assigned_contact_id' => 'int',
            'repeat' => 'int',
            'key_list_id' => 'int',
            'favorite' => 'bool',
            'attachments_count' => 'int',
            'comments_count' => 'int',
            'linked_entities_count' => 'int'
        ]);

        $this->response = reset($result);
    }
}
