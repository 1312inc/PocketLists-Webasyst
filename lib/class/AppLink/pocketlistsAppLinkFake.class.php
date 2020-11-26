<?php

/**
 * Class pocketlistsAppLinkFake
 */
class pocketlistsAppLinkFake implements pocketlistsAppLinkInterface
{
    /**
     * @return string
     */
    public function getApp()
    {
        return 'fake';
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return [];
    }

    /**
     * @param string $term
     * @param int    $count
     *
     * @return array
     */
    public function autocomplete($term, $params = [], $count = 10)
    {
        return [];
    }

    /**
     * @param pocketlistsItemLinkModel $model
     *
     * @return pocketlistsAppLinkInterface
     */
    public function setItemLinkModel(pocketlistsItemLinkModel $model)
    {}

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function getLinkUrl(pocketlistsItemLink $itemLink)
    {
        return '';
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return waModel
     */
    public function getAppEntity(pocketlistsItemLink $itemLink)
    {
        return null;
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function getEntityNum(pocketlistsItemLink $itemLink)
    {
        return '';
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return array
     * @throws waException
     */
    public function getExtraData(pocketlistsItemLink $itemLink)
    {
        return [];
    }

    /**
     * @return array
     */
    public function getLinkRegexs()
    {
        return [];
    }

    /**
     * @param array $regex
     * @param string $type
     *
     * @return int|null
     */
    public function getEntityIdByLinkRegexs($regex, $type)
    {
        return  0;
    }

    /**
     * @return string
     */
    public function getAppIcon()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * @return pocketlistsItemsCount
     */
    public function countItems()
    {
        return new pocketlistsItemsCount();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getBannerHtml()
    {
        return '';
    }

    /**
     * @param pocketlistsContact|null $user
     * @param string|null             $accessTo
     *
     * @return bool
     */
    public function userCanAccess(pocketlistsContact $user = null, $accessTo = null)
    {
        return false;
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderPreview(pocketlistsItemLink $itemLink)
    {
        return '';
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderAutocomplete(pocketlistsItemLink $itemLink)
    {
        return '';
    }

    public function countItemsForApp(array $params)
    {
        return 0;
    }

    public function getItemsForApp(array $params)
    {
        return [];
    }
}
