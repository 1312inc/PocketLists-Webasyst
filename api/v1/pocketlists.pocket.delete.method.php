<?php

class pocketlistsPocketDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $pocket_id = (int) $this->get('pocket_id');

        if ($pocket_id < 1) {
            return null;
        }

        $plf = pl2()->getEntityFactory(pocketlistsPocket::class);
        $pocket = $plf->findById($pocket_id);
        if (!$pocket) {
            return null;
        }

        if (!$plf->delete($pocket)) {
            throw new waAPIException('error', _w('Error while deleting pocket'));
        }
        $this->saveLog(
            pocketlistsLog::ENTITY_POCKET,
            pocketlistsLog::ACTION_DELETE,
            [[
                'pocket_id' => $pocket_id,
                'name' => $pocket->getName(),
            ]]
        );
    }
}
