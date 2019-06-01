<?php

/**
 * Class pocketlistsUser
 */
class pocketlistsUser extends pocketlistsContact
{
    /**
     * @var pocketlistsUserSettings
     */
    private $settings;

    /**
     * @return pocketlistsUserSettings
     */
    public function getSettings()
    {
        if ($this->settings === null) {
            $this->settings = new pocketlistsUserSettings();
        }

        return $this->settings;
    }

    /**
     * @param pocketlistsUserSettings $settings
     *
     * @return pocketlistsUser
     */
    public function setSettings(pocketlistsUserSettings $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return int
     * @throws waException
     */
    public function hasLinkedApps()
    {
        /** @var pocketlistsAppLinkInterface[] $apps */
        $apps = pl2()->getLinkedApp();

        if (!$apps) {
            return 0;
        }

        /** @var pocketlistsAppLinkInterface $app */
        foreach ($apps as $app) {
            if ($app->userCanAccess($this)) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * @return int|null
     * @throws waException
     */
    public function getAppCount()
    {
        $count = pl2()->getEntityCounter()->countTodoUndoneWithUserPrioritiesItems();

        return $count ? $count->getCount() : null;
    }
}
