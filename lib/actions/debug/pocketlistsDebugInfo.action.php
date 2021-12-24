<?php

class pocketlistsDebugInfoAction extends waViewAction
{
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new waException('Access denied.', 403);
        }

        if (!waSystemConfig::isDebug()) {
            throw new waException('Enable debug mode.', 403);
        }

        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contactFactory->getTeammates(pocketlistsRBAC::getAccessContacts());

        $filename = 'pocketlists/debug_info.log';
        waFiles::delete(sprintf('%s/../logs/%s', wa()->getAppPath(), $filename), true);

        foreach ($teammates as $teammate) {
            waLog::log(sprintf('====== User %d ======', $teammate->getId()), $filename);
            waLog::log(serialize($teammate), $filename);
            waLog::log(
                sprintf(
                    'user pl2 rights: %s',
                    json_encode($teammate->getContact()->getRights(pocketlistsHelper::APP_ID))
                ),
                $filename
            );
            waLog::log(
                sprintf('user webasyst rights: %s', json_encode($teammate->getContact()->getRights('webasyst'))),
                $filename
            );

            waLog::log(
                sprintf(
                    'user lists rights: %s',
                    json_encode(pocketlistsRBAC::getAccessListForContact($teammate->getContact()->getId()))
                ),
                $filename
            );
        }

        $this->redirect(wa()->getAppUrl());
    }
}
