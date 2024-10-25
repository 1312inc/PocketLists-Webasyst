<?php

class pocketlistsListsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $list_ids = $this->get('id');

        if (empty($list_ids)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id', 400));
        } elseif (!is_array($list_ids)) {
            throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
        }

        /** @var pocketlistsListFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsList::class);
        $list_ids = array_unique($list_ids);

        $logs = [];
        $lists = $plf->findByFields('id', $list_ids, true);
        foreach ($lists as $list) {
            $plf->delete($list);
            $logs[] = [
                'id'        => $list->getId(),
                'pocket_id' => $list->getPocketId(),
                'name'      => $list->getName()
            ];
        }
        if ($logs) {
            $this->saveLog(
                pocketlistsLog::ENTITY_LIST,
                pocketlistsLog::ACTION_DELETE,
                $logs
            );
        }
    }
}
