<?php

/**
 * Class pocketlistsAppLinkShop
 */
class pocketlistsAppLinkShop extends pocketlistsAppLinkAbstract
{
    const TYPE_ORDER = 'order';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $this->enabled = parent::isEnabled();
        if ($this->enabled === false) {
            return false;
        }

        $this->enabled = class_exists('shopOrder');

        return $this->enabled;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return [
            self::TYPE_ORDER,
        ];
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return 'shop';
    }

    /**
     * @param        $type
     * @param string $term
     * @param int    $count
     *
     * @return array
     */
    public function autocomplete($term, $type = '', $count = 10)
    {
        $result = [];

        foreach ($this->getTypes() as $entityType) {
            $method = sprintf('autocomplete%s', ucfirst($entityType));
            if (method_exists($this, $method)) {
                $result[] = [
                    'app'      => $this->getApp(),
                    'type'     => $entityType,
                    'entities' => $this->$method($term, $count),
                ];
            }
        }

        return $result;
    }

    protected function autocompleteOrder($term, $count)
    {
        $result = [];

        if (!wa()->getUser()->getRights('shop', 'orders')) {
            return $result;
        }

        // @see shopBackendAutocompleteController::ordersAutocomplete

        // first, assume $q is encoded $order_id, so decode
        $dq = shopHelper::decodeOrderId($term);
        if (!$dq) {
            $dq = shopBackendAutocompleteController::decodeOrderId($term);
        }

        $orders = $dq ? $this->getOrders($dq, $count) : [];
        $cnt = count($orders);
        if ($cnt < $count) {
            $orders = array_merge($orders, $this->getOrders($term, $count - $cnt));
        }

        foreach ($orders as $order) {
            $linkEntity = new pocketlistsItemLinkModel(
                [
                    'app'         => $this->getApp(),
                    'entity_type' => 'order',
                    'entity_id'   => $order['id'],
                ]
            );
            $this->setItemLinkModel($linkEntity);

            $result[] = [
                'label' => $linkEntity->renderAutocomplete(),
                'value' => shopHelper::encodeOrderId($order['id']),
                'data'  => [
                    'model'   => $linkEntity->getAttributes(),
                    'preview' => $linkEntity->renderPreview(),
                ],
            ];
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getLinkUrl()
    {
        return sprintf('%s#/orders/id=%s/', wa()->getAppUrl('shop'), $this->getItemLinkModel()->entity_id);
    }

    /**
     * @return shopOrder|waModel|false
     * @throws waException
     */
    public function getAppEntity()
    {
        try {
            return new shopOrder($this->getItemLinkModel()->entity_id);
        } catch (waException $ex) {
            return false;
        }
    }

    /**
     * @return array
     * @throws waException
     */
    public function getExtraData()
    {
        $order = $this->getAppEntity();

        $order_data_array = $order->dataArray();
        $order_data_array['contact'] = $order->contact_essentials;
        $order_data_array['state'] = $order['state'];

        return [
            'order'                => $order_data_array,
            'last_action_datetime' => $order->last_action_datetime,
            'link'                 => $this->getLinkUrl(),
        ];
    }

    /**
     * @return array
     */
    public function getLinkRegexs()
    {
        return [
            'order' => [
                '.*/shop/\?action=orders.*id=(\d+).*',
                '.*/shop/\?action=orders#/orders/edit/(\d+)/',
                '.*/shop/#/orders/.*id=(\d+).*',
            ],
        ];
    }

    /**
     * @return string
     */
    public function getAppIcon()
    {
        return '<i class="icon16 pl-wa-app-icon" style="background-image: url('.wa()->getAppStaticUrl(
                'shop'
            ).'img/shop.png); background-size: 16px 16px;"></i>';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Shop-Script';
    }

    /**
     * @param      $q
     * @param null $limit
     *
     * @return array
     */
    private function getOrders($q, $limit = null)
    {
        $order_model = new shopOrderModel();
        $limit = $limit ? $limit : self::LIMIT;

        $orders = $order_model->autocompleteById($q, $limit);
        if (!$orders) {
            return $order_model->autocompleteById($q, $limit, true);
        }

        return $orders;
    }

    /**
     * @param pocketlistsUser|null $user
     *
     * @return bool
     * @throws waException
     */
    public function userCanAccess(pocketlistsUser $user = null)
    {
        if ($user === null) {
            $user = wa(pocketlistsHelper::APP_ID)->getConfig()->getUser();
        }

        return pocketlistsRBAC::canUseShopScript($user);
    }
}
