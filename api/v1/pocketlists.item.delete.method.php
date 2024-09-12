<?php

class pocketlistsItemDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $item_ids = $this->get('id');

        if (empty($item_ids)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id', 400));
        } elseif (!is_array($item_ids)) {
            throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
        }

        /** @var pocketlistsItemFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsItem::class);
        $item_ids = array_unique($item_ids);

        $logs = [];
        $items = $plf->findByFields('id', $item_ids, true);
        foreach ($items as $item) {
            $plf->delete($item);
            $logs[] = [
                'id'      => $item->getId(),
                'list_id' => $item->getListId(),
                'name'    => $item->getName()
            ];
        }
        if ($logs) {
            pocketlistsLogService::multipleAdd(
                pocketlistsLog::ENTITY_ITEM,
                pocketlistsLog::ACTION_DELETE,
                $logs
            );
        }
    }
}
