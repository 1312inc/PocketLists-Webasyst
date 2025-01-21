<?php

class pocketlistsAttachmentsAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $files = $this->readBodyAsJson();
        if (empty($files)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($files)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

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
        foreach ($files as &$_file) {
            /** set default */
            $f_name = ifset($_file, 'file_name', null);
            $_file = [
                'id'              => null,
                'item_id'         => ifset($_file, 'item_id', null),
                'file_name'       => $f_name,
                'size'            => null,
                'ext'             => pocketlistsAttachment::getExtension($f_name),
                'upload_datetime' => date('Y-m-d H:i:s'),
                'download_url'    => '',
                'preview_url'     => '',
                'uuid'            => ifset($_file, 'uuid', null),
                'file'            => ifset($_file, 'file', null),
                'success'         => true,
                'errors'          => [],
            ];

            if (empty($_file['item_id'])) {
                $_file['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'item_id');
            } elseif (!is_numeric($_file['item_id'])) {
                $_file['errors'][] = sprintf_wp('Invalid type %s', 'item_id');
            } elseif ($_file['item_id'] < 1 || !in_array($_file['item_id'], $item_ids)) {
                $_file['errors'][] = _w('Item not found');
            }

            if (empty($_file['file_name'])) {
                $_file['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'file_name');
            } elseif (!is_string($_file['file_name'])) {
                $_file['errors'][] = sprintf_wp('Invalid type %s', 'file_name');
            }

            if (empty($_file['file'])) {
                $_file['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'file');
            } elseif (!is_string($_file['file'])) {
                $_file['errors'][] = sprintf_wp('Invalid type %s', 'file');
            }

            if (!empty($_file['uuid'])) {
                if (!is_string($_file['uuid'])) {
                    $_file['errors'][] = sprintf_wp('Invalid type %s', 'uuid');
                } elseif (in_array($_file['uuid'], $uuids)) {
                    $_file['errors'][] = _w('Attachment with UUID exists');
                }
            }

            $hash = md5(mt_rand(0, mt_rand(5000, 100000)).microtime());
            if (isset($result[$hash])) {
                $hash = md5(mt_rand(0, mt_rand(5000, 100000)));
            }
            $result[$hash] = $_file;
            if (empty($_file['errors'])) {
                $result[$hash]['file'] = null;
                $attachments[$_file['item_id']][$hash] = $_file;
            } else {
                $result[$hash]['errors'] = $_file['errors'];
                $result[$hash]['success'] = false;
            }
        }

        $result_ok = array_filter($result, function ($c) {
            return $c['success'];
        });
        $result_err = array_diff_key($result, $result_ok);
        if (!empty($result_ok)) {
            foreach ($attachments as $_item_id => $_attachments) {
                try {
                    $pl_attachments = $this->updateFiles($_item_id, $_attachments);
                    if (count($_attachments) === count($pl_attachments)) {
                        $list_id = ifset($items, $_item_id, 'list_id', null);
                        foreach ($_attachments as $_hash => $_at) {
                            $pl_attachment = current($pl_attachments);
                            if (empty($pl_attachment['error'])) {
                                $result_ok[$_hash] = $pl_attachment;
                                $result_ok[$_hash]['list_id'] = $list_id;
                            } else {
                                $result_ok[$_hash]['success'] = false;
                                $result_ok[$_hash]['errors'] = [$pl_attachment['error']];
                            }
                            next($pl_attachments);
                        }
                    }
                } catch (waException $e) {
                }
            }

            $logs = array_values(array_filter($result_ok, function ($a) {
                return empty($a['errors']);
            }));

            if ($logs) {
                pl2()->getModel(pocketlistsItem::class)->updateById(
                    array_filter(array_unique(array_column($logs, 'item_id'))),
                    ['activity_datetime' => date('Y-m-d H:i:s')]
                );
            }

            $this->saveLog(
                pocketlistsLog::ENTITY_ATTACHMENT,
                pocketlistsLog::ACTION_ADD,
                $logs
            );
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($result_ok, $result_err),
            [
                'id',
                'item_id',
                'file_name',
                'ext',
                'size',
                'upload_datetime',
                'download_url',
                'preview_url',
                'uuid'
            ], [
                'id' => 'int',
                'item_id' => 'int',
                'size' => 'int',
                'upload_datetime' => 'datetime'
            ]
        );
    }
}
