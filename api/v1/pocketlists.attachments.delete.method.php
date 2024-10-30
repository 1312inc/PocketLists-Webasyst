<?php

class pocketlistsAttachmentsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $data = $this->readBodyAsJson();
        if (empty($data)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => _w('Missing `data`'),
                'data'        => []
            ];
            return;
        } elseif (!is_array($data)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => _w('Type error `data`'),
                'data'        => []
            ];
            return;
        }

        $items = [];
        $attachments = [];
        $attachment_ids = array_unique(array_filter(array_column($data, 'id')));
        $item_ids = array_unique(array_column($data, 'item_id'));
        $list_id_available = pocketlistsRBAC::getAccessListForContact($this->getUser()->getId());

        /** @var pocketlistsAttachmentFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsAttachment::class);
        if (!empty($item_ids)) {
            $items = pl2()->getModel(pocketlistsItem::class)
                ->select('id, list_id')
                ->where('id IN (:item_ids)', ['item_ids' => $item_ids])
                ->fetchAll('id');
        }
        if (!empty($attachment_ids)) {
            $attachments = $plf->findByFields('id', $attachment_ids, true);
            $attachment_ids = [];

            /** @var pocketlistsAttachment $a */
            foreach ($attachments as $a) {
                $attachment_ids[] = $a->getId();
            }
        }

        // validate
        foreach ($data as &$_data) {
            /** set default */
            $_data = [
                'id'        => ifset($_data, 'id', null),
                'item_id'   => ifset($_data, 'item_id', null),
                'file_name' => null,
                'file_type' => null,
                'url'       => null,
                'uuid'      => null,
                'file'      => null,
                'success'   => null,
                'errors'    => [],
            ];

            if (empty($_data['id'])) {
                $_data['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_data['id'])) {
                $_data['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!in_array($_data['id'], $attachment_ids)) {
                $_data['errors'][] = _w('Attachment not found');
            }

            if (empty($_data['item_id'])) {
                $_data['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'item_id');
            } elseif (!is_numeric($_data['item_id'])) {
                $_data['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'item_id');
            } else {
                $item = ifset($items, $_data['item_id'], null);
                if ($item === null) {
                    $_data['errors'][] = _w('Item not found');
                } elseif (!in_array($item['list_id'], $list_id_available)) {
                    $_data['errors'][] = _w('Access denied');
                }
            }

            if (!empty($_data['errors'])) {
                $_data['success'] = false;
            }
        }

        $data_ok = array_filter($data, function ($d) {
            return is_null($d['success']);
        });
        $data_err = array_diff_key($data, $data_ok);
        if (!empty($data_ok)) {
            $logs = [];
            foreach ($attachments as $_attachment) {
                try {
                    $id = $_attachment->getId();
                    if ($plf->delete($_attachment)) {
                        $success = true;
                        $logs[] = [
                            'id'       => $id,
                            'list_id'  => ifempty($items, $_attachment->getItemId(), 'list_id', null),
                            'item_id'  => $_attachment->getItemId(),
                            'filename' => $_attachment->getFilename(),
                            'filetype' => $_attachment->getFiletype()
                        ];
                    } else {
                        $success = false;
                    }
                    foreach ($data_ok as &$_data_ok) {
                        if ($_data_ok['id'] == $id) {
                            $_data_ok['success'] = $success;
                            break;
                        }
                    }
                } catch (waException $we) {

                }
            }
            $this->saveLog(
                pocketlistsLog::ENTITY_ATTACHMENT,
                pocketlistsLog::ACTION_DELETE,
                $logs
            );
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($data_ok, $data_err),
            [
                'id'
            ], [
                'id' => 'int'
            ]
        );
    }
}
