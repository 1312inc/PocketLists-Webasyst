<?php

/**
 * Class pocketlistsFactory
 */
class pocketlistsFactory
{
    /**
     * @var pocketlistsEntity
     */
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
     * @param      $field
     * @param null $value
     * @param bool $all
     * @param bool $limit
     *
     * @return pocketlistsEntity[]|pocketlistsEntity
     * @throws waException
     */
    public function findByFields($field, $value = null, $all = false, $limit = false)
    {
        if (is_array($field)) {
            $limit = $all;
            $all = $value;
            $value = false;
        }

        $data = $this->getModel()->getByField($field, $value, $all, $limit);

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
            $lists[] = pl2()->getHydrator()->hydrate($this->createNew(), $datum);
        }

        return $all === false ? reset($lists) : $lists;
    }

    public function insert(pocketlistsEntity $entity, $fields = [], $type = waModel::INSERT_ON_DUPLICATE_KEY_UPDATE)
    {
        $dbFields = $this->getModel()->getMetadata();

        $data = pl2()->getHydrator()->extract($entity, $fields, $dbFields);
        unset($data['id']);

        $id = $this->getModel()->insert($data, $type);

        if ($id) {
            if (method_exists($entity, 'setId')) {
                $entity->setId($id);
            }

            return true;
        }

        return false;
    }
}
