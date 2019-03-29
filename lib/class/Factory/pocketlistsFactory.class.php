<?php

/**
 * Class pocketlistsFactory
 */
class pocketlistsFactory
{
    protected $entity;

    /**
     * @var kmModelExt
     */
    protected $model;

    /**
     * @param mixed $entity
     *
     * @return pocketlistsFactory
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    public function createNew()
    {
        $entity = $this->getEntity();

        return new $entity();
    }

    /**
     * @return kmModelExt
     * @throws waException
     */
    protected function getModel()
    {
        return wa(pocketlistsHelper::APP_ID)->getConfig()->getModel($this->getEntity());
    }

    /**
     * @param $id
     *
     * @return array|mixed
     * @throws waException
     */
    public function findById($id)
    {
        $data = $this->getModel()->getById($id);
        $all = false;

        if (is_array($id) && !is_array($this->getModel()->getTableId())) {
            $all = true;
        }

        return $this->generateWithData($data, $all);
    }


    /**
     * @param array $data
     * @param bool  $all
     *
     * @return array|mixed
     */
    public function generateWithData(array $data, $all = false)
    {
        if ($all === false) {
            $data = [$data];
        }

        $lists = [];

        foreach ($data as $datum) {
            $lists[] = wa()->getConfig()->getHydrator()->hydrate($this->createNew(), $datum);
        }

        return $all === false ? reset($lists) : $lists;
    }
}
