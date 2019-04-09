<?php

/**
 * Interface pocketlistsAppLinkInterface
 */
interface pocketlistsAppLinkInterface
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
     * @return pocketlistsAppLinkInterface
     */
    public function setItemLinkModel(pocketlistsItemLinkModel $model);

    /**
     * @return pocketlistsItemLinkModel
     */
    public function getItemLinkModel();

    /**
     * @return string
     */
    public function getLinkUrl();

    /**
     * @return waModel|shopOrder
     */
    public function getAppEntity();

    /**
     * @return array
     * @throws waException
     */
    public function getExtraData();

    /**
     * @return array
     */
    public function getLinkRegexs();

    /**
     * @return string
     */
    public function getAppIcon();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function countItems();

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @return string
     */
    public function getBannerHtml();

    /**
     * @param pocketlistsUser|null $user
     *
     * @return bool
     */
    public function userCanAccess(pocketlistsUser $user = null);
}
