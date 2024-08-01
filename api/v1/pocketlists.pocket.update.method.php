<?php

class pocketlistsPocketUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $pocket_id = ifset($_json, 'id', null);
        $name = ifset($_json, 'name', null);
        $color = ifset($_json, 'color', null);

        if (!isset($pocket_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (!is_numeric($pocket_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'id'), 400);
        } elseif ($pocket_id < 1) {
            throw new waAPIException('not_found', _w('Pocket not found'), 404);
        } elseif (!is_string($name)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'name'), 400);
        } elseif (isset($color) && (!is_string($color) || !array_key_exists($color, pocketlistsStoreColor::getColors()))) {
            throw new waAPIException('unknown_value', _w('Unknown color'), 400);
        }

        /** @var pocketlistsPocketFactory $pocket_factory */
        $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);
        $pocket = $pocket_factory->findById($pocket_id);
        if (!$pocket) {
            throw new waAPIException('not_found', _w('Pocket not found'), 404);
        }

        $pocket->setName($name);
        if (isset($color)) {
            $pocket->setColor($color);
        }

        if ($pocket_factory->save($pocket)) {
            $this->response = [
                'id'       => (int) $pocket->getId(),
                'sort'     => (int) $pocket->getSort(),
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
