<?php

/**
 * Class pocketlistsAppLinkShopRepository
 */
class pocketlistsAppLinkShopRepository
{
    /**
     * @var shopOrder[]
     */
    private $orders = [];

    /**
     * @param $orderId
     *
     * @return shopOrder
     * @throws waException
     */
    public function getOrder($orderId)
    {
        if (!isset($this->orders[$orderId])) {
            $this->orders[$orderId] = new shopOrder($orderId);
        }

        return $this->orders[$orderId];
    }
}
