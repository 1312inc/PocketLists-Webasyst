<?php

/**
 * Class pocketlistsItemLinkAutocompleter
 */
class pocketlistsItemLinkAutocompleter
{
    /**
     * @var array
     */
    protected $linkEntities;

    /**
     * @var pocketlistsItemLinkInterface[]
     */
    protected $linkers;

    /**
     * @var array
     */
    protected $result;

    /**
     * pocketlistsItemLinkAutocompleter constructor.
     */
    public function __construct()
    {
        $this->linkEntities = wa()->getConfig()->getLinkedApps();

        if (empty($this->linkEntities)) {
            return false;
        }

        $this->linkers = [];
        foreach ($this->linkEntities as $entity) {
            $class = sprintf('pocketlistsItemLink%s', ucfirst($entity));
            if (class_exists($class)) {
                $class = new $class();
                if ($class instanceof pocketlistsItemLinkInterface) {
                    $this->linkers[$entity] = $class;
                }
            }
        }
    }

    /**
     * @param       $term
     * @param array $types
     *
     * @return pocketlistsItemLinkAutocompleter
     */
    public function process($term, $types = [])
    {
        $this->result = [];

        foreach ($this->linkers as $type => $linker) {
            if ($types && !in_array($type, $types)) {
                continue;
            }

            $this->result[$type] = $linker->autocomplete($term);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFlattenResult()
    {
        $flatResult = [];

        array_walk_recursive(
            $this->result,
            function ($a) use (&$flatResult) {
                $flatResult[] = $a;
            }
        );

        return $flatResult;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}
