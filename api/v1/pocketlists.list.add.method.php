<?php

class pocketlistsListAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $name = (string) ifset($_json, 'name', '');
        $type = (string) ifset($_json, 'type', 'checklist');
        $pocket_id = (int) ifset($_json, 'pocket_id', 0);

        if (empty($name)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'name'), 400);
        } elseif ($pocket_id < 1) {
            throw new waAPIException('not_found', _w('Pocket not found'), 404);
        } elseif (!in_array($type, ['checklist', 'notes'])) {
            throw new waAPIException('unknown_value', _w('Unknown type'), 400);
        }

        $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->findById($pocket_id);
        if (empty($pocket)) {
            throw new waAPIException('not_found', _w('Pocket not found'), 404);
        } elseif (pocketlistsRBAC::contactHasAccessToPocket($pocket) != pocketlistsRBAC::RIGHT_ADMIN) {
            throw new waAPIException('forbidden', _w('Access denied'), 403);
        }

        /** @var pocketlistsList $list */
        $list = pl2()->getEntityFactory(pocketlistsList::class)->createNew();
        $list->setName($name)
            ->setType($type)
            ->setPocket($pocket)
            ->setColor($pocket->getColor())
            ->setContact($this->getUser())
            ->setCreateDatetime(date('Y-m-d H:i:s'));
        $list_factory->save($list);

        $this->response = [
            'list_id' => $list->getId(),
            'item_id' => $list->getKeyItemId()
        ];
    }
}
