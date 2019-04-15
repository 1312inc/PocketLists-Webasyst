<?php

/**
 * Class pocketlistsFactory
 */
class pocketlistsFactory
{
    protected $entity;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var kmModelExt
     */
    protected $model;

    /**
     * @param string $entity
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

    /**
     * @return mixed
     */
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
        return pl2()->getModel($this->getEntity());
    }

    /**
     * @param $id
     *
     * @return array|mixed
     * @throws waException
     */
    public function findById($id)
    {
        $cached = $this->getFromCache($id);
        if ($cached) {
            return $cached;
        }

        $data = $this->getModel()->getById($id);
        if (!$data) {
            return null;
        }

        $all = false;

        if (is_array($id) && !is_array($this->getModel()->getTableId())) {
            $all = true;
        }

        $entities = $this->generateWithData($data, $all);

        if (!$all && $entities) {
            $this->cache($id, $entities);
        }

        return $entities;
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
            $obj = pl2()->getHydrator()->hydrate($this->createNew(), $datum);

            $lists[] = $obj;
        }

        return $all === false ? reset($lists) : $lists;
    }

    public function insert(pocketlistsEntity $entity, $fields = [], $type = waModel::INSERT_ON_DUPLICATE_KEY_UPDATE)
    {
        $data = pl2()->getHydrator()->extract($entity, $fields, $this->getDbFields());
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

    /**
     * @param pocketlistsEntity $entity
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $entity)
    {
        if (method_exists($entity, 'getId')) {
            return $this->getModel()->deleteById($entity->getId());
        }

        throw new waException('No id in entity');
    }

    /**
     * @param pocketlistsEntity $entity
     * @param array             $fields
     *
     * @return bool|waDbResultUpdate|null
     * @throws waException
     */
    public function update(pocketlistsEntity $entity, $fields = [])
    {
        if (method_exists($entity, 'getId')) {
            $data = pl2()->getHydrator()->extract($entity, $fields, $this->getDbFields());
            unset($data['id']);

            return $this->getModel()->updateById($entity->getId(), $data);
        }

        throw new waException('No id in entity');
    }

    /**
     * @param pocketlistsEntity $entity
     * @param array             $fields
     *
     * @return bool|waDbResultUpdate|null
     * @throws waException
     */
    public function save(pocketlistsEntity $entity, $fields = [])
    {
        if (method_exists($entity, 'getId')) {
            if ($entity->getId()) {
                return $this->update($entity, $fields);
            }

            return $this->insert($entity, $fields);
        }

        throw new waException('No id in entity');
    }

    /**
     * @return array
     * @throws waException
     */
    public function getDbFields()
    {
        return $this->getModel()->getMetadata();
    }

    /**
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param array $cache
     *
     * @return pocketlistsFactory
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param $key
     *
     * @return bool|pocketlistsEntity
     */
    protected function getFromCache($key)
    {
        if (isset($this->cache[$this->entity][$key])) {
            return $this->cache[$this->entity][$key];
        }

        return false;
    }

    /**
     * @param $key
     * @param $entity
     */
    protected function cache($key, $entity)
    {
        if (!isset($this->cache[$this->entity])) {
            $this->cache[$this->entity] = [];
        }

        $this->cache[$this->entity][$key] = $entity;
    }
}
