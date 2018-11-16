<?php

interface pocketlistsItemLinkInterface
{
    /**
     * @return array
     */
    public function getTypes();

    /**
     * @param string $term
     * @param int    $count
     *
     * @return array
     */
    public function autocomplete($type, $term, $count = 10);
}
