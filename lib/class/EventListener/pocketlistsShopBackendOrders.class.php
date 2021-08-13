<?php

final class pocketlistsShopBackendOrders
{
    /**
     * @param $params
     *
     * @return array
     * @throws waException
     */
    public function execute(&$params)
    {
        /** @var waSmarty3View $view */
        $view = new waSmarty3View(wa());

        $return = [];

        $app = pl2()->getLinkedApp('shop');

        if (!$app->isEnabled()) {
            return $return;
        }

        if (!$app->userCanAccess()) {
            return $return;
        }

        $viewParams = [
            'wa_app_static_url' => wa()->getAppStaticUrl(pocketlistsHelper::APP_ID),
            'app' => $app,
            'plurl' => wa()->getAppUrl(pocketlistsHelper::APP_ID),
            'user' => pl2()->getUser(),
            'externalApp' => 'shop',
        ];

        foreach (['sidebar_bottom_li'] as $hook) {
            $template = wa()->getAppPath(
                sprintf(
                    'templates/include%s/app_hook/shop.backend_orders.%s.html',
                    pl2()->getUI2TemplatePath(null, 'shop'),
                    $hook
                ),
                pocketlistsHelper::APP_ID
            );

            if (file_exists($template)) {
                try {
                    if (method_exists($this, $hook)) {
                        $viewParams = array_merge($viewParams, $this->$hook());
                    }

                    $view->assign('params', $viewParams);
                    $return[$hook] = $view->fetch($template);
                } catch (Exception $ex) {
                    pocketlistsHelper::logError(sprintf('%s error %s', $hook, $ex->getMessage()), $ex);
                }
            }
        }

        return $return;
    }

    private function sidebar_bottom_li()
    {
        return [];
    }
}