<?php

/**
 * Class pocketlistsShopActions
 */
class pocketlistsShopActions extends waJsonActions
{
    /**
     * @throws waException
     */
    public function loadOrderIconsAction()
    {
        $app = pl2()->getLinkedApp('shop');

        if (!$app->isEnabled()) {
            return;
        }

        if (!$app->userCanAccess()) {
            return;
        }

        $orders = waRequest::post('orders');

        if (empty($orders)) {
            return;
        }

        /** @var pocketlistsItemLinkModel $linkFactory */
        $linkFactory = pl2()->getModel(pocketlistsItemLink::class);

        $this->response = $linkFactory->countLinkedItemsByAppAndEntities(
            $app->getApp(),
            pocketlistsAppLinkShop::TYPE_ORDER,
            $orders
        );
    }
}
