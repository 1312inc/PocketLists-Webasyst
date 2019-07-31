<?php

/**
 * Class pocketlistsProPluginLabelCrudActions
 */
class pocketlistsProPluginLabelCrudActions extends pocketlistsJsonActions
{
    /**
     * @var pocketlistsProPluginLabelFactory
     */
    protected $labelFactory;

    /**
     * pocketlistsProPluginLabelCrudController constructor.
     *
     * @throws waException
     */
    public function __construct()
    {
        $this->labelFactory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
    }

    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function updateAction()
    {
        $label = $this->findLabel();

        $data = waRequest::post('data', [], waRequest::TYPE_ARRAY);

        $label
            ->setColor($data['color'])
            ->setName($data['name']);

        if (!$this->labelFactory->save($label)) {
            $this->setError('Label update error');
        }
    }

    /**
     * @throws waException
     */
    public function createAction()
    {
        /** @var pocketlistsProPluginLabel $label */
        $label = pl2()->getHydrator()->hydrate(
            $this->labelFactory->createNew(),
            waRequest::post('data', [], waRequest::TYPE_ARRAY)
        );

        if (!$this->labelFactory->insert($label)) {
            $this->setError('Label create error');
        } else {
            $this->response = pl2()->getHydrator()->extract($label);
        }
    }

    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function getAction()
    {
        $this->response = pl2()->getHydrator()->extract($this->findLabel());
    }

    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function deleteAction()
    {
        if (!$this->labelFactory->delete($this->findLabel())) {
            $this->setError('Label delete error');
        }
    }

    /**
     * @return pocketlistsProPluginLabel
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    private function findLabel()
    {
        /** @var pocketlistsProPluginLabel $label */
        $label = $this->labelFactory->findById($this->getId());

        if (!$label instanceof pocketlistsProPluginLabel) {
            throw new pocketlistsNotFoundException('Label not found');
        }

        return $label;
    }
}
