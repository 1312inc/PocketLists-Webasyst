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

        foreach ($this->getTypes() as $type) {
            $method = sprintf('autocomplete%s', ucfirst($type));
            if (method_exists($this, $method)) {
                $result[$type] = $this->$method($term, $count);
            }
        }

        return $result;
    }

    protected function autocompleteOrder($term, $count)
    {
        $result = [];

        $orders = (new shopOrderModel())
            ->select('*')
            ->where("id like :term", ['term' => $term.'%'])
            ->limit($count)
            ->fetchAll();

        foreach ($orders as $order) {
            $result[] = 'Order '.shopHelper::encodeOrderId($order['id']);
        }

        return $result;
    }
}