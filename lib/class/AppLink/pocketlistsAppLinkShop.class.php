<?php

/**
 * Class pocketlistsAppLinkShop
 */
class pocketlistsAppLinkShop extends pocketlistsAppLinkAbstract implements pocketlistsAppLinkInterface
{
    const TYPE_ORDER = 'order';
    const APP        = 'shop';

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
        return self::APP;
    }

    /**
     * @param       $term
     * @param array $params
     * @param       $count
     *
     * @return array
     * @throws waException
     */
    protected function autocompleteOrder($term, $params = [], $count)
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

        /** @var pocketlistsItemLinkFactory $itemlinkFactory */
        $itemlinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);

        foreach ($orders as $order) {
            /** @var pocketlistsItemLink $linkEntity */
            $linkEntity = $itemlinkFactory->createNew();

            $linkEntity
                ->setApp($this->getApp())
                ->setEntityType('order')
                ->setEntityId($order['id']);

            $result[] = [
                'label' => $this->renderAutocomplete($linkEntity),
                'value' => shopHelper::encodeOrderId($order['id']),
                'data' => [
                    'model' => pl2()->getHydrator()->extract($linkEntity, [], $itemlinkFactory->getDbFields()),
                    'preview' => $this->renderPreview($linkEntity, $params),
                ],
            ];
        }

        return $result;
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function getLinkUrl(pocketlistsItemLink $itemLink)
    {
        return sprintf('%s?action=orders#/order/%s/', wa()->getAppUrl('shop'), $itemLink->getEntityId());
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function getEntityNum(pocketlistsItemLink $itemLink)
    {
        return shopHelper::encodeOrderId($itemLink->getEntityId());
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return bool|shopOrder
     */
    public function getAppEntity(pocketlistsItemLink $itemLink)
    {
        try {
            return pl2()->getEntityRepository('pocketlistsAppLinkShop')->getOrder($itemLink->getEntityId());
        } catch (waException $ex) {
            return false;
        }
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return array
     * @throws waException
     */
    public function getExtraData(pocketlistsItemLink $itemLink)
    {
        $order = $itemLink->getAppEntity();

        $order_data_array = $order->dataArray();
        $order_data_array['contact'] = $order->contact_essentials;
        $order_data_array['state'] = $order['state'];

        return [
            'order' => $order_data_array,
            'last_action_datetime' => $order->last_action_datetime,
            'link' => $this->getLinkUrl($itemLink),
        ];
    }

    /**
     * @return array
     */
    public function getLinkRegexs()
    {
        return [
            self::TYPE_ORDER => [
                '.*/shop/\?action=orders.*id=(\d+).*',
                '.*/shop/\?action=orders#/orders/edit/(\d+)/',
                '.*/shop/#/orders/.*id=(\d+).*',
            ],
        ];
    }

    /**
     * @param array $regex
     * @param string $type
     *
     * @return int|null
     */
    public function getEntityIdByLinkRegexs($regex, $type)
    {
        switch ($type) {
            case self::TYPE_ORDER:
                return (int) $regex[1];

            default:
                return null;
        }
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
     * @param pocketlistsContact|null $user
     * @param string|null             $accessTo
     *
     * @return bool
     * @throws waException
     */
    public function userCanAccess(pocketlistsContact $user = null, $accessTo = null)
    {
        if ($user === null) {
            $user = wa(pocketlistsHelper::APP_ID)->getConfig()->getUser();
        }

        return pocketlistsRBAC::canUseShopScript($user);
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderAutocomplete(pocketlistsItemLink $itemLink)
    {
        return sprintf('<span>%s %s</span>', _w('Order'), shopHelper::encodeOrderId($itemLink->getEntityId()));
    }

    public function countItemsForApp(array $params)
    {
        return pl2()->getModel(pocketlistsItem::class)->countAppItems(
            $this->getApp(),
            $params['entityType'],
            $params['entityId'],
            $params['date'],
            $params['status']
        );
    }

    public function getItemsForApp(array $params)
    {
        return pl2()->getModel(pocketlistsItem::class)->getAppItems(
            $this->getApp(),
            $params['entityType'],
            $params['entityId'],
            $params['date'],
            $params['status'],
            $params['limit'],
            $params['offset']
        );
    }
}
