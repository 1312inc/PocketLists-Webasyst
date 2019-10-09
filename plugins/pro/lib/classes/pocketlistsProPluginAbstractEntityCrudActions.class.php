<?php

/**
 * Class pocketlistsProPluginAbstractEntityCrudActions
 */
abstract class pocketlistsProPluginAbstractEntityCrudActions extends pocketlistsJsonActions
{
    /**
     * @return pocketlistsFactory
     * @throws waException
     */
    abstract protected function getFactory();

    /**
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function updateAction()
    {
        $entity = $this->findEntity();

        $data = waRequest::post('data', [], waRequest::TYPE_ARRAY);

        unset($data['id']);
        pl2()->getHydrator()->hydrate($entity, $data);

        if (method_exists($entity, 'setUpdateDatetime')) {
            $entity->setUpdatedDatetime(date('Y-m-d H:i:s'));
        }

        if (!$this->getFactory()->update($entity)) {
            $this->setError(sprintf('%s update error', $this->getEntityName()));
        }
    }

    /**
     * @throws waException
     */
    public function createAction()
    {
        $entity = pl2()->getHydrator()->hydrate(
            $this->getFactory()->createNew(),
            waRequest::post('data', [], waRequest::TYPE_ARRAY)
        );

        if (!$this->getFactory()->insert($entity)) {
            $this->setError(sprintf('%s create error', $this->getEntityName()));
        } else {
            $this->response = pl2()->getHydrator()->extract($entity);
        }
    }

    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function getAction()
    {
        $this->response = pl2()->getHydrator()->extract($this->findEntity());
    }

    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function deleteAction()
    {
        if (!$this->getFactory()->delete($this->findEntity())) {
            $this->setError(sprintf('%s delete error', $this->getEntityName()));
        }
    }

    /**
     * @return pocketlistsEntity
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    protected function findEntity()
    {
        $entity = $this->getFactory()->findById($this->getId());

        if (!is_object($entity)) {
            throw new pocketlistsNotFoundException(sprintf('%s not found', $this->getEntityName()));
        }

        return $entity;
    }
}
