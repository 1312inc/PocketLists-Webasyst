<?php

class pocketlistsPocketAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $name = ifset($_json, 'name', null);
        $color = (string) ifset($_json, 'color', '');

        if (!isset($name)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'name'), 400);
        } elseif (!array_key_exists($color, pocketlistsStoreColor::getColors())) {
            throw new waAPIException('unknown_value', _w('Unknown color'), 400);
        }

        /** @var pocketlistsPocketFactory $pocket_factory */
        $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = $pocket_factory->createNew();

        $pocket->setName($name)
            ->setColor($color);
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


        return $this->response = ['id' => $pocket->getId()];
    }
}
