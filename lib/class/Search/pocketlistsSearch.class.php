<?php

/**
 * Class pocketlistsService
 */
abstract class pocketlistsSearch
{
    /**
     * @var string
     */
    protected $term;

    /**
     * @var int
     */
    protected $foundCount = 0;

    /**
     * @var array
     */
    protected $results = [];

    /**
     * static constructor.
     *
     * @param string $term
     */
    public function __construct($term)
    {
        $this->term = (string)$term;
    }

    /**
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param string $term
     *
     * @return static
     */
    public function setTerm($term)
    {
        $this->term = (string)$term;

        return $this;
    }

    /**
     * @return int
     */
    public function getFoundCount()
    {
        return $this->foundCount;
    }

    /**
     * @return pocketlistsEntity[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return static
     *
     * @throws waDbException
     * @throws waException
     */
    abstract public function search();
}
