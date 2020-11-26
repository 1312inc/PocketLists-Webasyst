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
            sprintf('templates/include/app_hook/webasyst.backend_header.%s.html', $hook),
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