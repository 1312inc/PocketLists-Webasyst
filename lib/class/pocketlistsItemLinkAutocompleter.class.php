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
     * @param array $params
     *
     * @return $this
     * @throws waException
     */
    public function process($term, $params = [])
    {
        $this->result = [];
        $linked = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp();

        if (!$linked) {
            return $this;
        }

        /**
         * @var string                      $app
         * @var pocketlistsAppLinkInterface $linker
         */
        foreach ($linked as $app => $linker) {
            if ($params && !empty($params['types']) && !in_array($app, $params['types'])) {
                continue;
            }

            if (!$linker->userCanAccess()) {
                continue;
            }

            $this->result = array_merge($this->result, $linker->autocomplete($term, $params));
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
