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
    public function autocomplete($term, $params = [], $count = 10);

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function getLinkUrl(pocketlistsItemLink $itemLink);

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function getEntityNum(pocketlistsItemLink $itemLink);

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return shopOrder
     */
    public function getAppEntity(pocketlistsItemLink $itemLink);

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return array
     * @throws waException
     */
    public function getExtraData(pocketlistsItemLink $itemLink);

    /**
     * @return array
     */
    public function getLinkRegexs();

    /**
     * @param array $regex
     * @param string $type
     *
     * @return int|null
     */
    public function getEntityIdByLinkRegexs($regex, $type);

    /**
     * @return string
     */
    public function getAppIcon();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return pocketlistsItemsCount
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
     * @param pocketlistsContact|null $user
     * @param null|string             $accessTo
     *
     * @return bool
     */
    public function userCanAccess(pocketlistsContact $user = null, $accessTo = null);

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderPreview(pocketlistsItemLink $itemLink);

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderAutocomplete(pocketlistsItemLink $itemLink);

    /**
     * @param array $params
     *
     * @return int
     */
    public function countItemsForApp(array $params);

    /**
     * @param array $params
     *
     * @return array
     */
    public function getItemsForApp(array $params);
}
