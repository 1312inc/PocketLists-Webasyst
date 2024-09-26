<?php

class pocketlistsPocketAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $name = ifset($_json, 'name', null);
        $color = ifset($_json, 'color', null);
        $sort = ifset($_json, 'sort', 0);
        $rank = ifset($_json, 'rank', '');
        $uuid = ifset($_json, 'uuid', null);

        if (!isset($name)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'name'), 400);
        } elseif (!is_string($name)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'name'), 400);
        } elseif (isset($color) && (!is_string($color) || !array_key_exists($color, pocketlistsStoreColor::getColors()))) {
            throw new waAPIException('unknown_value', _w('Unknown color'), 400);
        } elseif (!is_numeric($sort)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'sort'), 400);
        } elseif (!is_string($rank)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'rank'), 400);
        } elseif (isset($uuid) && !is_string($uuid)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'uuid'), 400);
        }

        /** @var pocketlistsPocketFactory $pocket_factory */
        $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = $pocket_factory->createNew();

        $pocket->setName($name)
            ->setSort($sort)
            ->setRank($rank);
        if (isset($color)) {
            $pocket->setColor($color);
        }
        if (isset($uuid)) {
            $pocket->setUuid($uuid);
        }
        if ($pocket_factory->save($pocket)) {
            (new waContactRightsModel())->save(
                $this->getUser()->getId(),
                wa()->getApp(),
                pocketlistsRBAC::POCKET_ITEM.'.'.$pocket->getId(),
                pocketlistsRBAC::RIGHT_ADMIN
            );
            $this->saveLog(
                pocketlistsLog::ENTITY_POCKET,
                pocketlistsLog::ACTION_ADD,
                [[
                    'pocket_id' => $pocket->getId(),
                    'name'  => $name,
                    'color' => $color,
                    'sort'  => $sort,
                    'rank'  => $rank,
                    'uuid'  => $uuid
                ]]
            );
        } else {
            throw new waAPIException('error', _w('Some error on save pocket'), 500);
        }

        return $this->response = [
            'id'       => $pocket->getId(),
            'sort'     => $pocket->getSort(),
            'rank'     => $pocket->getRank(),
            'name'     => $pocket->getName(),
            'color'    => $pocket->getColor(),
            'passcode' => $pocket->getPasscode(),
            'uuid'     => $pocket->getUuid()
        ];
    }
}
