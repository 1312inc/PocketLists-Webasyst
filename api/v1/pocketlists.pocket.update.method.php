<?php

class pocketlistsPocketUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $action = (ifset($_json, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]);
        $pocket_id = ifset($_json, 'id', null);
        $name = ifset($_json, 'name', null);
        $color = ifset($_json, 'color', null);
        $sort = ifset($_json, 'sort', null);
        $rank = ifset($_json, 'rank', null);

        if (!isset($pocket_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (!is_numeric($pocket_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'id'), 400);
        } elseif ($pocket_id < 1) {
            throw new waAPIException('not_found', _w('Pocket not found'), 404);
        } elseif (isset($name) && !is_string($name)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'name'), 400);
        } elseif (isset($color) && (!is_string($color) || !array_key_exists($color, pocketlistsStoreColor::getColors()))) {
            throw new waAPIException('unknown_value', _w('Unknown color'), 400);
        } elseif (isset($sort) && !is_numeric($sort)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'sort'), 400);
        } elseif (isset($rank)) {
            if (!is_string($rank)) {
                throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'rank'), 400);
            } elseif (!pocketlistsSortRank::rankValidate($rank)) {
                throw new waAPIException('unknown_value', _w('Invalid rank value'), 400);
            }
        }

        /** @var pocketlistsPocketFactory $pocket_factory */
        $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = $pocket_factory->findById($pocket_id);
        if (!$pocket) {
            throw new waAPIException('not_found', _w('Pocket not found'), 404);
        }

        if ($action === self::ACTIONS[0]) {
            // patch
            $name = ifset($name, $pocket->getName());
            $color = ifset($color, $pocket->getColor());
            $sort = ifset($sort, $pocket->getSort());
            $rank = ifset($rank, $pocket->getRank());
        }

        $pocket->setName($name)
            ->setColor($color)
            ->setSort($sort)
            ->setRank($rank);

        if ($pocket_factory->save($pocket)) {
            $this->response = [
                'id'       => (int) $pocket->getId(),
                'sort'     => $pocket->getSort(),
                'rank'     => $pocket->getRank(),
                'name'     => $pocket->getName(),
                'color'    => $pocket->getColor(),
                'passcode' => $pocket->getPasscode(),
                'uuid'     => $pocket->getUuid()
            ];
        } else {
            throw new waAPIException('error', _w('Some error on save pocket'), 500);
        }
    }
}
