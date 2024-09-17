<?php

class pocketlistsAttachmentDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $invalid = false;
        $attachment_ids = [];
        $data = $this->readBodyAsJson();

        if (empty($data)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($data)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        // validate
        foreach ($data as &$_data) {
            if (!isset($_data['item_id'])) {
                $_data['error'] = sprintf_wp('Missing required parameter: “%s”.', 'item_id');
            } elseif (!is_numeric($_data['item_id'])) {
                $_data['error'] = sprintf_wp('Type error parameter: “%s”.', 'item_id');
            } elseif ($_data['item_id'] < 1) {
                $_data['error'] = _w('Item not found');
            }

            if (!isset($_data['attachment_id'])) {
                $_data['error'] = sprintf_wp('Missing required parameter: “%s”.', 'attachment_id');
            } elseif (!is_array($_data['attachment_id'])) {
                $_data['error'] = sprintf_wp('Type error parameter: “%s”.', 'attachment_id');
            } else {
                foreach ($_data['attachment_id'] as $_attachment_id) {
                    if (!is_numeric($_attachment_id)) {
                        $_data['error'] = sprintf_wp('Type error parameter: “%s”.', 'attachment_id');
                    }
                    $attachment_ids[] = $_attachment_id;
                }
            }
            $invalid = $invalid || isset($_data['error']);
        }

        if (!$invalid) {
            $item_ids = array_unique(array_column($data, 'item_id'));
            $attachment_ids = array_unique($attachment_ids);
            $items = pl2()->getModel(pocketlistsItem::class)
                ->select('id, list_id')
                ->where('id IN (:item_ids)', ['item_ids' => $item_ids])
                ->fetchAll('id');

            /** @var pocketlistsAttachmentFactory $plf */
            $plf = pl2()->getEntityFactory(pocketlistsAttachment::class);
            $attachments = $plf->findByFields('id', $attachment_ids, true);

            $attachment_ids = [];
            /** @var pocketlistsAttachment $a */
            foreach ($attachments as $a) {
                $attachment_ids[] = $a->getId();
            }

            foreach ($data as &$_data) {
                $item = ifset($items, $_data['item_id'], null);
                if ($item === null) {
                    $_data['error'] = _w('Item not found');
                } elseif (!empty($_data['list_id']) && !pocketlistsRBAC::canAccessToList($item['list_id'])) {
                    throw new waAPIException('forbidden', _w('Access denied'), 403);
                }
                foreach ($_data['attachment_id'] as $_attachment_id) {
                    if (!in_array($_attachment_id, $attachment_ids)) {
                        $_data['error'] = _w('Attachment not found');
                    }
                }

                $invalid = $invalid || isset($_data['error']);
            }
        }

        if (!$invalid) {
            $logs = [];
            foreach ($attachments as $_attachment) {
                try {
                    if ($plf->delete($_attachment)) {
                        $logs[] = [
                            'id'       => $_attachment->getId(),
                            'list_id'  => ifempty($items, $_attachment->getItemId(), 'list_id', null),
                            'item_id'  => $_attachment->getItemId(),
                            'filename' => $_attachment->getFilename(),
                            'filetype' => $_attachment->getFiletype()
                        ];
                    }
                } catch (waException $we) {

                }
            }
            pocketlistsLogService::multipleAdd(
                pocketlistsLog::ENTITY_ATTACHMENT,
                pocketlistsLog::ACTION_DELETE,
                $logs
            );
        }

        $this->response = $data;
    }
}
