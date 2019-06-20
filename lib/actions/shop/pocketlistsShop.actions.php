<?php

/**
 * Class pocketlistsShopActions
 */
class pocketlistsShopActions extends waJsonActions
{
    /**
     * @var pocketlistsAppLinkInterface
     */
    protected $app;

    /**
     * @var bool
     */
    protected $fail = false;

    /**
     * @throws waException
     */
    public function preExecute()
    {
        wa()->getStorage()->close();

        $this->app = pl2()->getLinkedApp('shop');

        if (!$this->app->isEnabled()) {
            $this->fail = true;

            return;
        }

        if (!$this->app->userCanAccess()) {
            $this->fail = true;

            return;
        }
    }

    /**
     * @throws waException
     */
    public function loadOrdersIconAction()
    {
        if ($this->fail) {
            return;
        }

        $orders = waRequest::post('orders');

        if (empty($orders)) {
            return;
        }

        $counts = pl2()->getEntityCounter()->countLinkedItemsByAppAndEntities(
            $this->app,
            pocketlistsAppLinkShop::TYPE_ORDER,
            $orders
        );

        /** @var pocketlistsItemsCount $count */
        foreach ($counts as $entityId => $count) {
            $this->response[] = [
                'entity_id'      => $entityId,
                'count_entities' => $count->getCount(),
            ];
        }
    }

    /**
     * @throws waException
     */
    public function loadOrderIconAction()
    {
        if ($this->fail) {
            return;
        }

        $order = waRequest::post('order');

        if (empty($order)) {
            return;
        }

        $count = pl2()->getEntityCounter()->countLinkedItemByAppAndEntities(
            $this->app,
            pocketlistsAppLinkShop::TYPE_ORDER,
            $order
        );

        $this->response = [
            'entity_id'      => $order,
            'count_entities' => $count->getCount(),
        ];
    }
}
