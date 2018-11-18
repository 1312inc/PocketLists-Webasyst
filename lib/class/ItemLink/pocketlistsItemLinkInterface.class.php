<?php

/**
 * Interface pocketlistsItemLinkInterfacez
 */
interface pocketlistsItemLinkInterface
{
    /**
     * @return string
     */
    public function getApp();

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
    public function autocomplete($term, $type = '', $count = 10);

    /**
     * @param pocketlistsItemLinkModel $model
     *
     * @return pocketlistsItemLinkInterface
     */
    public function setItemLinkModel(pocketlistsItemLinkModel $model);

    /**
     * @return pocketlistsItemLinkModel
     */
    public function getItemLinkModel();

    /**
     * @return string
     */
    public function getLink();
}
