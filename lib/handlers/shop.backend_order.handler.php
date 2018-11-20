<?php

/**
 * Class pocketlistsShopBackend_orderHandler
 */
class pocketlistsShopBackend_orderHandler extends waEventHandler
{
    /**
     * @param $params
     *
     * @return array
     */
    public function execute(&$params)
    {
        /** @var waSmarty3View $view */
        $view = new waSmarty3View(wa());

        $return = [];

        $app = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp('shop');

        $viewParams = [
            'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
            'app'               => $app,
            'order'             => $params,
            'plurl'             => wa()->getAppUrl(pocketlistsHelper::APP_ID),
        ];

        foreach (['aux_info', 'action_link', 'info_section', 'title_suffix', 'action_button'] as $hook) {
            $template = wa()->getAppPath(
                sprintf('templates/include/app_hook/shop.backend_order.%s.html', $hook),
                pocketlistsHelper::APP_ID
            );

            if (file_exists($template)) {
                $view->assign('params', $viewParams);
                $return[$hook] = $view->fetch($template);
            }
        }

        return $return;
    }
}
