<?php

/**
 * Class pocketlistsEntity
 */
abstract class pocketlistsEntity implements pocketlistsHydratableInterface
{
    /**
     * @var array
     */
    protected $_dataFields = [];

    /**
     * @param string $field
     *
     * @return mixed
     * @throws pocketlistsLogicException
     */
    public function getDataField($field)
    {
        if (!array_key_exists($field, $this->_dataFields)) {
            throw new pocketlistsLogicException(sprintf('Field %s not found in %s', $field, static::class));
        }

        return $this->_dataFields[$field];
    }

    /**
     * @param string $field
     * @param mixed  $value
     */
    public function setDataField($field, $value)
    {
        $this->_dataFields[$field] = $value;
    }

    /**
     * @return array
     */
    public function getDbFields()
    {
        return $this->getModel()->getMetadata();
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     */
    public function afterHydrate($data = [])
    {
    }

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
    }

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function afterExtract(array &$fields)
    {
    }
}
