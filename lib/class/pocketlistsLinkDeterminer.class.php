<?php

/**
 * Class pocketlistsLinkDeterminer
 */
class pocketlistsLinkDeterminer
{
    protected $link;

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return pocketlistsLinkDeterminer
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @param $link
     *
     * @return array|bool
     * @throws waException
     *
     */
    public function getAppTypeId($link)
    {
        $this->setLink($link);

        $linkers = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp();

        foreach ($linkers as $app) {
            if (!$app->userCanAccess()) {
                continue;
            }

            $types = $app->getLinkRegexs();
            foreach ($types as $type => $regexs) {
                foreach ($regexs as $regex) {
                    if (preg_match('|' . $regex . '|iu', $link, $matches)) {
                        $id = $app->getEntityIdByLinkRegexs($matches, $type);
                        if ($id) {
                            return [
                                'app' => $app->getApp(),
                                'entity_type' => $type,
                                'entity_id' => $id,
                            ];
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param $app
     *
     * @return string
     * @throws waException
     */
    public function getAppIcon($app)
    {
        return wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($app)->getAppIcon();
    }
}
