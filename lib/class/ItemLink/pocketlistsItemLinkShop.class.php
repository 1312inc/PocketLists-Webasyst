<?php

class pocketlistsItemLinkShop extends pocketlistsItemLink implements pocketlistsItemLinkInterface
{
    const TYPE_ORDER = 'order';

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
     * @param        $type
     * @param string $term
     * @param int    $count
     *
     * @return array
     */
    public function autocomplete($type, $term, $count = 10)
    {
        $result = [];

        switch ($type) {
            case self::TYPE_ORDER:
                $orders = (new shopOrderModel())
                    ->select('*')
                    ->where("id like '%s:term%'", ['term' => $term])
                    ->limit($count)
                    ->fetchAll();

                foreach ($orders as $order) {
                    $result[] = 'Order '. shopHelper::encodeOrderId($order['id']);
                }
        }

        return $result;
    }
}