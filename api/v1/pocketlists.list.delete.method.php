<?php

class pocketlistsListDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $list_id = (int) $this->get('list_id');

        if ($list_id < 1) {
            return null;
        }

        $plf = pl2()->getEntityFactory(pocketlistsList::class);
        $list = $plf->findById($list_id);
        if (!$list) {
            return null;
        }

        if (!$plf->delete($list)) {
            throw new waAPIException('error', _w('Error while deleting list and it items'));
        }
    }
}
