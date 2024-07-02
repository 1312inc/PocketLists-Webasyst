<?php

class pocketlistsItemDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $item_id = (int) $this->get('item_id');

        if ($item_id < 1) {
            return null;
        }

        $plf = pl2()->getEntityFactory(pocketlistsItem::class);
        $item = $plf->findById($item_id);
        if (!$item) {
            return null;
        }

        if (!$plf->delete($item)) {
            throw new waAPIException('error', _w('Error while deleting item'));
        }
    }
}
