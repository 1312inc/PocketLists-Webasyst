<?php

/**
 * Class pocketlistsAppLinkShop
 */
class pocketlistsAppLinkShop extends pocketlistsAppLinkAbstract
{
    const TYPE_ORDER = 'order';
    const APP = 'shop';

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
     * @param string $term
     * @param array  $params
     * @param int    $count
     *
     * @return array
     */
    public function autocomplete($term, $params = [], $count = 10)
    {
        $result = [];

        foreach ($this->getTypes() as $entityType) {
            $method = sprintf('autocomplete%s', ucfirst($entityType));
            if (method_exists($this, $method)) {
                $entities = $this->$method($term, $params, $count);
                if ($entities) {
                    $result[] = [
                        'app'      => $this->getApp(),
                        'type'     => $entityType,
                        'entities' => $entities,
                        'params'   => $params
                    ];
                }
            }
        }

        return $result;
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
                'data'  => [
                    'model'   => pl2()->getHydrator()->extract($linkEntity, [], $itemlinkFactory->getDbFields()),
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
        return sprintf('%s#/orders/id=%s/', wa()->getAppUrl('shop'), $itemLink->getEntityId());
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
            return new shopOrder($itemLink->getEntityId());
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
            'order'                => $order_data_array,
            'last_action_datetime' => $order->last_action_datetime,
            'link'                 => $this->getLinkUrl($itemLink),
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
        return sprintf(
            '<i class="icon16 pl-wa-app-icon" style="background-image: url(%simg/shop.png); background-size: 16px 16px;"></i>',
            wa()->getAppStaticUrl('shop')
        );
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
     * @param pocketlistsContact|null $user
     *
     * @return bool
     * @throws waException
     */
    public function userCanAccess(pocketlistsContact $user = null)
    {
        if ($user === null) {
            $user = wa(pocketlistsHelper::APP_ID)->getConfig()->getUser();
        }

        return pocketlistsRBAC::canUseShopScript($user);
    }

    /**
     * @param pocketlistsItemLink $itemLink
     * @param array               $params
     *
     * @return string
     * @throws waException
     */
    public function renderPreview(pocketlistsItemLink $itemLink, $params = [])
    {
        if (!$itemLink->getEntityId() || !$itemLink->getEntityType()) {
            return '';
        }

        $template = wa()->getAppPath(
            sprintf(
                'templates/include/item_linked_entities/%s.%s.preview.html',
                $this->getApp(),
                $itemLink->getEntityType()
            ),
            pocketlistsHelper::APP_ID
        );

        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_ITEM_RENDER_LINKED, $this);
        pl2()->waDispatchEvent($event);
        $pluginRender = $event->getResponse();
        $render = !empty($pluginRender['preview']) ? $pluginRender['preview'] : '';

        if ($this->isEnabled() && !$render && file_exists($template)) {
            if (!$itemLink->getAppEntity()) {
                return '';
            }

            $this->getView()->clearAllAssign();
            $vars = [
                'link'   => $itemLink,
                'extra'  => $this->getExtraData($itemLink),
                'params' => $params,
            ];
            $this->getView()->assign($vars);

            $render = $this->getView()->fetch($template);
        }

        return $render;
    }

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return string
     */
    public function renderAutocomplete(pocketlistsItemLink $itemLink)
    {
        $template = wa()->getAppPath(
            sprintf(
                'templates/include/item_linked_entities/%s.%s.autocomplete.html',
                $this->getApp(),
                $itemLink->getEntityType()
            ),
            pocketlistsHelper::APP_ID
        );

//        $render = wa()->event('item.render_autocomplete', $this);

        if (file_exists($template)) {
            $this->getView()->clearAllAssign();
            $this->getView()->assign('link', $itemLink);

            $render = $this->getView()->fetch($template);
        } else {
            $render = (string)$this;
        }

        return $render;
    }
}
