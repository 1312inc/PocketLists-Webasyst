<?php

class pocketlistsAttachmentAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $files = $this->readBodyAsJson();
        if (empty($files)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($files)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $err = false;
        $result = [];
        $items = [];
        $attachments = [];
        $item_ids = array_unique(array_column($files, 'item_id'));
        $uuids = array_column($files, 'uuid');
        if (!empty($item_ids)) {
            /** @var pocketlistsItemModel $item_model */
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $items = $item_model->select('id, list_id')->where('id IN (:item_ids)', ['item_ids' => $item_ids])->fetchAll('id');
            $item_ids = array_keys($items);
        }
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('attachment', $uuids);
            $uuids = array_keys($uuids);
        }

        /** validate */
        foreach ($files as $_file) {
            $errors = [];
            if (empty($_file['item_id'])) {
                $errors[] = sprintf_wp('Missing required parameter: “%s”.', 'item_id');
            } elseif (!is_numeric($_file['item_id'])) {
                $errors[] = sprintf_wp('Invalid type %s', 'item_id');
            } elseif ($_file['item_id'] < 1 || !in_array($_file['item_id'], $item_ids)) {
                $errors[] = _w('Item not found');
            }

            if (empty($_file['file_name'])) {
                $errors[] = sprintf_wp('Missing required parameter: “%s”.', 'file_name');
            } elseif (!is_string($_file['file_name'])) {
                $errors[] = sprintf_wp('Invalid type %s', 'file_name');
            }

            if (empty($_file['file'])) {
                $errors[] = sprintf_wp('Missing required parameter: “%s”.', 'file');
            } elseif (!is_string($_file['file'])) {
                $errors[] = sprintf_wp('Invalid type %s', 'file');
            }

            if (!empty($_file['uuid'])) {
                if (!is_string($_file['uuid'])) {
                    $errors[] = sprintf_wp('Invalid type %s', 'uuid');
                } elseif (in_array($_file['uuid'], $uuids)) {
                    $errors[] = _w('Attachment with UUID exists');
                }
            }

            $hash = md5(mt_rand(0, mt_rand(5000, 100000)));
            $result[$hash] = $_file;
            $result[$hash]['file'] = null;
            if (empty($errors)) {
                $attachments[$_file['item_id']][$hash] = $_file;
            } else {
                $err = true;
                $result[$hash]['errors'] = $errors;
            }
        }

        if (!$err) {
            foreach ($attachments as $_item_id => $_attachments) {
                try {
                    $pl_attachments = $this->updateFiles($_item_id, $_attachments);
                    if (count($_attachments) === count($pl_attachments)) {
                        $list_id = ifset($items, $_item_id, 'list_id', null);
                        foreach ($_attachments as $_hash => $_at) {
                            $pl_attachment = current($pl_attachments);
                            if (empty($pl_attachment['error'])) {
                                $result[$_hash] += $pl_attachment;
                                $result[$_hash] += ['list_id' => $list_id];
                            } else {
                                $this->http_status_code = 400;
                                $result[$_hash]['errors'] = [$pl_attachment['error']];
                            }
                            next($pl_attachments);
                        }
                    }
                } catch (waException $e) {
                }
            }
            $this->saveLog(
                pocketlistsLog::ENTITY_ATTACHMENT,
                pocketlistsLog::ACTION_ADD,
                array_values(array_filter($result, function ($a) {
                    return empty($a['errors']);
                }))
            );
        }

        $this->response = $this->filterFields(
            $result,
            [
                'id',
                'item_id',
                'file_name',
                'file_type',
                'url',
                'uuid',
                'errors'
            ], [
                'id' => 'int',
                'item_id' => 'int'
            ]
        );
    }
}
