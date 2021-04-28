<?php

final class pocketlistsWebasystBackendHeader
{
    /**
     * @param $params
     *
     * @return array
     */
    public function execute(&$params)
    {
        if (!wa()->getUser()->getRights('pocketlists')) {
            return;
        }

        /** @var waSmarty3View $view */
        $view = new waSmarty3View(wa());

        $hook = 'header_bottom';

        $template = wa()->getAppPath(
            sprintf(
                'templates/include%s/app_hook/webasyst.backend_header.%s.html',
                wa()->whichUI() === '1.3' ? '-legacy' : '',
                $hook
            ),
            pocketlistsHelper::APP_ID
        );

        $data = [
            'pl2_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
        ];

        return array (
            $hook => $view->fetch($template, $data),
        );
    }
}