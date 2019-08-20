<?php

/**
 * Class pocketlistsProPluginCreateItemAction
 */
class pocketlistsProPluginCreateItemAction implements kmAutomationActionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var pocketlistsContact
     */
    protected $assignContact;

    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * @var DateTime|null
     */
    protected $dueDate;

    /**
     * @var pocketlistsList|null
     */
    protected $list;

    /**
     * @return string
     */
    public function getName()
    {
        return 'create_item';
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'name'           => $this->name,
            'assign_contact' => $this->assignContact ? $this->assignContact->getId() : null,
            'priority'       => $this->priority,
            'due_date'       => $this->dueDate ? $this->dueDate->format('Y-m-d 00:00:00') : null,
            'list'           => $this->list ? $this->list->getId() : null,
        ];
    }

    /**
     * @return mixed
     */
    public function perform()
    {
        /** @var pocketlistsItemFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        $item = $factory->createNew();
        $item->setName($this->name);
    }

    /**
     * @return string
     */
    public function buildHtml()
    {
        return <<<HTML

HTML;
    }

    /**
     * @return string
     */
    public function editHtml()
    {
        return <<<HTML

HTML;
    }

    /**
     * @param string $json
     */
    public function load($json)
    {
        $json = json_decode($json, true);

        foreach ($json as $name => $item) {
            switch ($name) {
                case 'name':
                    $this->name = $item;
                    break;

                case 'priority':
                    $this->priority = $item;
                    break;
            }
        }
    }
}