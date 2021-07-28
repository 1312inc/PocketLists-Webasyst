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
                pl2()->getUI2TemplatePath(null, 'webasyst'),
                $hook
            ),
            pocketlistsHelper::APP_ID
        );

        $data = [
            'pl2_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
            'externalApp' => 'webasyst',
        ];

        return array (
            $hook => $view->fetch($template, $data),
        );
    }
}
