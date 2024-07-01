<?php

class pocketlistsItemAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $list_id = (int) ifset($_json, 'list_id', 0);
        $name = (string) ifset($_json, 'name', '');
        $note = (string) ifset($_json, 'note', '');
        $assigned_contact_id = ifset($_json, 'assigned_contact_id', null);
        $priority = (int) ifset($_json, 'priority', 0);
        $due_datetime = ifset($_json, 'due_datetime', null);
        $files = ifset($_json, 'files', null);

        if (empty($list_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'list_id'), 400);
        } elseif ($list_id < 1) {
            throw new waAPIException('not_found', _w('List not found'), 404);
        }

        /** @var pocketlistsListFactory $list_factory */
        $list_factory = pl2()->getEntityFactory(pocketlistsList::class);
        $list = $list_factory->findById($list_id);
        if (empty($list)) {
            throw new waAPIException('not_found', _w('List not found'), 404);
        }

        /** @var pocketlistsItemFactory $item_factory */
        $item_factory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        $item = $item_factory->createNew();

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
                throw new waAPIException('not_found', _w('Contact not found'), 404);
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
        if (isset($due_datetime)) {
            $dt = date_create($due_datetime);
            if ($dt) {
                $item->setDueDate($dt->format('Y-m-d'))
                    ->setDueDatetime($dt->format('Y-m-d H:i:s'));
            } else {
                throw new waAPIException('unknown_value', _w('Unknown date'), 400);
            }
        }

        $item->setName($name)
            ->setListId($list_id)
            ->setContact($this->getUser())
            ->setCreateDatetime(date('Y-m-d H:i:s'))
            ->setPriority($priority)
            ->setNote($note);

        if ($item_factory->insert($item)) {
            $this->response = ['id' => $item->getId()];

            if ($files) {
                $attachments = [];

                /** @var pocketlistsFactory $attachment_factory */
                $attachment_factory = pl2()->getEntityFactory(pocketlistsAttachment::class);
                foreach ((array) $files as $_file) {
                    $item_file = base64_decode(ifset($_file, 'file', null));

                    /** download to temp directory */
                    $name = md5(uniqid(__METHOD__)).$_file['file_name'];
                    $temp_path = wa()->getTempPath('files');
                    waFiles::create($temp_path, true);
                    $tmp_name = $temp_path."/$name";
                    if (!file_put_contents($tmp_name, $item_file)) {
                        throw new waAPIException('server_error', _w('File could not be saved.'), 500);
                    }

                    /** @var pocketlistsUploadedFileVO $uploaded_file */
                    $uploaded_file = pocketlistsUploadedFileVO::createTempFromName($name);
                    $uploaded_file->setItemId($item->getId())
                        ->setName($_file['file_name']);

                    waFiles::move($tmp_name, $uploaded_file->getFullPath());

                    /** @var pocketlistsAttachment $attachment */
                    $attachment = $attachment_factory->createNew();
                    $attachment->setFilename($_file['file_name'])
                        ->setItemId($item->getId());
                    $attachment_factory->insert($attachment);
                    $attachments[] = $attachment;
                }
                $item->setAttachments($attachments);
            }
        } else {
            throw new waAPIException('error', _w('Error creating the element'));
        }
    }
}
