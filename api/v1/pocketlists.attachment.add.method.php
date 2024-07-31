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
        $attachments = [];
        $item_ids = array_unique(array_column($files, 'item_id'));
        if (!empty($item_ids)) {
            /** @var pocketlistsItemModel $item_model */
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $item_ids = $item_model->select('id')->where('id IN (:item_ids)', ['item_ids' => $item_ids])->fetchAll(null, true);
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

            if (!empty($_file['uuid']) && !is_string($_file['uuid'])) {
                $errors[] = sprintf_wp('Invalid type %s', 'uuid');
            }

            $hash = md5(mt_rand(0, mt_rand(5000, 100000)));
            $result[$hash] = $_file;
            if (empty($errors)) {
                $attachments[$_file['item_id']][$hash] = $_file;
            } else {
                $err = true;
                $result[$hash]['errors'] = $errors;
            }
        }

        if (!$err) {
            $path = wa()->getDataUrl('attachments', true, pocketlistsHelper::APP_ID);
            foreach ($attachments as $_item_id => $_attachments) {
                try {
                    $pl_attachments = $this->updateFiles($_item_id, $_attachments);
                    if (count($_attachments) === count($pl_attachments)) {
                        foreach ($_attachments as $_hash => $_at) {
                            $pl_attachment = current($pl_attachments);
                            if (empty($pl_attachment['error'])) {
                                $result[$_hash] += [
                                    'id'        => (int) ifset($pl_attachment, 'id', 0),
                                    'file_type' => ifset($pl_attachment, 'filetype', ''),
                                    'path'      => "$path/$_item_id/".ifset($pl_attachment, 'file_name', '')
                                ];
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
        }

        $this->response = $this->filterFields(
            $result,
            [
                'id',
                'item_id',
                'file_name',
                'file_type',
                'path',
                'uuid',
                'errors'
            ],
            [
                'id' => 'int',
                'item_id' => 'int'
            ]
        );
    }
}
