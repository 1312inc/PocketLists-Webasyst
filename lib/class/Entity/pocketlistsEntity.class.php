<?php

/**
 * Class pocketlistsEntity
 */
abstract class pocketlistsEntity implements pocketlistsHydratableInterface
{
    /**
     * @return array
     */
    public function getDbFields()
    {
        $meta = $this->getModel()->getMetadata();

        return $meta;
    }

    /**
     * @return array
     */
//    abstract protected function getDbFields();

    /**
     * @var kmModelExt
     */
    protected $_model;
    /**
     * @var array
     */
    protected $_model_sensitive_attributes = [];

    /**
     * @var string
     */
    protected $_uuid;

    /**
     * pocketlistsEntity constructor.
     *
     * @param null $model
     */
    public function __construct($model = null)
    {
        $this->setModel($model);
    }

    /**
     * @param kmModelExt $model
     *
     * @return static
     */
    protected function setModel($model)
    {
        if ($model instanceof kmModelExt) {
            $this->_model = $model;
        }

        return $this;
    }

    /**
     * @return kmModelExt
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param array $models
     *
     * @return static[]
     */
    public static function generate($models)
    {
        $obj = [];
        if (!$models) {
            return $obj;
        }
        foreach ($models as $id => $model) {
            if ($model instanceof kmModelExt) {
                $obj[$id] = new static($model);
            }
        }

        return $obj;
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected function removeSensitiveData($data)
    {
        foreach ($this->_model_sensitive_attributes as $key) {
            if (!empty($data[$key])) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Устанавливает данные в модель, предварительно удалив оттуда все данные,
     * которые не должны быть установлены пользователем
     *
     * @param $data
     */
    public function setAttributes($data)
    {
        $data = self::removeSensitiveData($data);
        $this->model->setAttributes($data);
    }

    /**
     * @param array $attr
     * @param int   $type
     *
     * @return bool|int|null|resource|waDbResultUpdate
     * @throws waDbException
     */
    public function save($attr = [], $type = 0)
    {
        return $this->model->save($attr, $type);
    }

    /**
     * @return array
     * @throws waDbException
     */
    public function getApiResponse($data = null)
    {
        if (!$this->model) {
            return [];
        }
        if ($this->model->hasAttribute('is_deleted') && $this->model->is_deleted) {
            $value = [
                'id'         => $this->model->pk,
                'is_deleted' => $this->model->is_deleted,
            ];
        } else {
            $value = $this->model->getAttributes();
        }

        if ($this->getUuid()) {
            $value['uuid'] = $this->getUuid();
        }

        return $value;
    }

    /**
     * Обновляет update_datetime у модели
     *
     * @return bool
     * @throws waDbException
     */
    public function touch()
    {
        return $this->save(['update_datetime']);
    }

    /**
     * @param string $uuid
     *
     * @return pocketlistsEntity
     */
    public function setUuid($uuid)
    {
        $this->_uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->_uuid;
    }

    public function afterHydrate()
    {
    }

    public function beforeExtract(array &$fields)
    {
    }
}
