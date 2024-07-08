<?php

class pocketlistsPocketDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $posket_id = (int) $this->get('pocket_id');

        if ($posket_id < 1) {
            return null;
        }

        $plf = pl2()->getEntityFactory(pocketlistsPocket::class);
        $posket = $plf->findById($posket_id);
        if (!$posket) {
            return null;
        }

        if (!$plf->delete($posket)) {
            throw new waAPIException('error', _w('Error while deleting pocket'));
        }
    }
}
