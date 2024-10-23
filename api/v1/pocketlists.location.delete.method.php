<?php

class pocketlistsLocationDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $location_ids = $this->get('id');

        if (empty($location_ids)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id', 400));
        } elseif (!is_array($location_ids)) {
            throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
        }

        /** @var pocketlistsLocationFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsLocation::class);
        $location_ids = array_unique($location_ids);

        $logs = [];
        $locations = $plf->findByFields('id', $location_ids, true);
        foreach ($locations as $location) {
            $plf->delete($location);
            $logs[] = [
                'id'   => $location->getId(),
                'name' => $location->getName()
            ];
        }
        $this->saveLog(
            pocketlistsLog::ENTITY_LOCATION,
            pocketlistsLog::ACTION_DELETE,
            $logs
        );
    }
}
