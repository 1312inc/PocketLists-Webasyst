<?php

/**
 * Class pocketlistsItemLinkAutocompleter
 */
class pocketlistsItemLinkAutocompleter
{
    /**
     * @var array
     */
    protected $result;

    /**
     * @param       $term
     * @param array $types
     *
     * @return $this
     * @throws waException
     */
    public function process($term, $types = [])
    {
        $this->result = [];
        $linked = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp();

        if (!$linked) {
            return $this;
        }

        foreach ($linked as $app => $linker) {
            if ($types && !in_array($app, $types)) {
                continue;
            }

            $this->result = array_merge($this->result, $linker->autocomplete($term));
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
