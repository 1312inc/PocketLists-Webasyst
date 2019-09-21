<?php

/**
 * Class pocketlistsFactory
 */
class pocketlistsFactory
{
    const DEFAULT_LIMIT = 30;
    const DEFAULT_OFFSET = 0;

    protected $entity;

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return static
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return static
     */
    public function resetLimitAndOffset()
    {
        $this->limit = 0;
        $this->offset = 0;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return static
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

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
    public function createNew()
    {
        $entity = $this->getEntity();

        return new $entity();
    }

    /**
     * @return waModel
     * @throws waException
     */
    public function getModel()
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
     * @return pocketlistsEntity[]|pocketlistsEntity
     * @throws waException
     */
    public function findAll()
    {
        $data = $this->getModel()->getAll();

        return $this->generateWithData($data, true);
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

    /**
     * @param pocketlistsEntity $entity
     * @param array             $fields
     * @param int               $type
     *
     * @return bool
     * @throws waException
     */
    public function insert(pocketlistsEntity $entity, $fields = [], $type = waModel::INSERT_ON_DUPLICATE_KEY_UPDATE)
    {
        $data = pl2()->getHydrator()->extract($entity, $fields, $this->getDbFields());

        /**
         * Before every entity insert
         * @event entity_insert.before
         *
         * @param pocketlistsEventInterface $event Event with pocketlistsEntity object
         * @return array Entity data to merge and insert
         */
        $event = new pocketlistsEvent(pocketlistsEventStorage::ENTITY_INSERT_BEFORE, $entity, ['data' => $data]);
        $eventResult = pl2()->waDispatchEvent($event);
        foreach ($eventResult as $plugin => $responseData) {
            if (!empty($responseData) && is_array($responseData)) {
                $data = array_merge($data, $responseData);
            }
        }

        unset($data['id']);

        $id = $this->getModel()->insert($data, $type);

        if ($id) {
            if (method_exists($entity, 'setId')) {
                $entity->setId($id);
            }

            /**
             * After every entity insert
             * @event entity_insert.after
             *
             * @param pocketlistsEventInterface $event Event with pocketlistsEntity object
             * @return void
             */
            $event = new pocketlistsEvent(pocketlistsEventStorage::ENTITY_INSERT_AFTER, $entity);
            pl2()->getEventDispatcher()->dispatch($event);

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
            /**
             * Before every entity delete
             * @event entity_delete.before
             *
             * @param pocketlistsEventInterface $event Event with pocketlistsEntity object
             * @return bool If false - entity delete will be canceled
             */
            $event = new pocketlistsEvent(pocketlistsEventStorage::ENTITY_DELETE_BEFORE, $entity);
            $eventResult = pl2()->waDispatchEvent($event);
            foreach ($eventResult as $plugin => $responseData) {
                if ($responseData === false) {
                    return false;
                }
            }

            $deleted = $this->getModel()->deleteById($entity->getId());

            /**
             * After every entity delete
             * @event entity_delete.after
             *
             * @param pocketlistsEventInterface $event Event with pocketlistsEntity object
             * @return void
             */
            $event = new pocketlistsEvent(pocketlistsEventStorage::ENTITY_DELETE_AFTER, $entity);
            pl2()->waDispatchEvent($event);

            return $deleted;
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

            /**
             * Before every entity update
             * @event entity_update.before
             *
             * @param pocketlistsEventInterface $event Event with pocketlistsEntity object
             * @return array Entity data to merge and update
             */
            $event = new pocketlistsEvent(pocketlistsEventStorage::ENTITY_UPDATE_BEFORE, $entity, ['data' => $data]);
            $eventResult = pl2()->waDispatchEvent($event);
            foreach ($eventResult as $plugin => $responseData) {
                if (!empty($responseData) && is_array($responseData)) {
                    $data = array_merge($data, $responseData);
                }
            }

            unset($data['id']);

            $updated = $this->getModel()->updateById($entity->getId(), $data);

            if ($updated) {
                /**
                 * After every entity update
                 *
                 * @event entity_update.after
                 * @param pocketlistsEventInterface $event Event with pocketlistsEntity object
                 * @return void
                 */
                $event = new pocketlistsEvent(pocketlistsEventStorage::ENTITY_UPDATE_AFTER, $entity, ['data' => $data]);
                pl2()->waDispatchEvent($event);
            }

            return $updated;
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
