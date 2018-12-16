<?php

/**
 * Class pocketlistsItemLinkFake
 */
class pocketlistsItemLinkFake implements pocketlistsItemLinkInterface
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
    public function autocomplete($term, $type = '', $count = 10)
    {
        return [];
    }

    /**
     * @param pocketlistsItemLinkModel $model
     *
     * @return pocketlistsItemLinkInterface
     */
    public function setItemLinkModel(pocketlistsItemLinkModel $model)
    {}

    /**
     * @return pocketlistsItemLinkModel
     */
    public function getItemLinkModel()
    {
        return new pocketlistsItemLinkModel();
    }

    /**
     * @return string
     */
    public function getLinkUrl()
    {
        return '';
    }

    /**
     * @return waModel
     */
    public function getEntity()
    {
        return null;
    }

    /**
     * @return array
     * @throws waException
     */
    public function getExtraData()
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
     * @return int
     */
    public function countItems()
    {
        return 0;
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
     * @param pocketlistsUser|null $user
     *
     * @return bool
     */
    public function userCanAccess(pocketlistsUser $user = null)
    {
        return false;
    }
}
