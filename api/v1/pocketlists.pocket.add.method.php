<?php

class pocketlistsPocketAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $name = ifset($_json, 'name', null);
        $color = ifset($_json, 'color', null);
        $sort = ifset($_json, 'sort', '0');
        $uuid = ifset($_json, 'uuid', null);

        if (!isset($name)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'name'), 400);
        } elseif (!is_string($name)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'name'), 400);
        } elseif (isset($color) && (!is_string($color) || !array_key_exists($color, pocketlistsStoreColor::getColors()))) {
            throw new waAPIException('unknown_value', _w('Unknown color'), 400);
        } elseif (!is_string($sort)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'sort'), 400);
        } elseif (isset($uuid) && !is_string($uuid)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'uuid'), 400);
        }

        /** @var pocketlistsPocketFactory $pocket_factory */
        $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = $pocket_factory->createNew();

        $pocket->setName($name)
            ->setSort($sort);
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
        } else {
            throw new waAPIException('error', _w('Some error on save pocket'), 500);
        }

        return $this->response = [
            'id'       => $pocket->getId(),
            'sort'     => $pocket->getSort(),
            'name'     => $pocket->getName(),
            'color'    => $pocket->getColor(),
            'passcode' => $pocket->getPasscode(),
            'uuid'     => $pocket->getUuid()
        ];
    }
}
