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

        $orders = (new shopOrderModel())
            ->select('*')
            ->where("id like :term", ['term' => $term.'%'])
            ->limit($count)
            ->fetchAll();

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
                'model'        => $linkEntity->getAttributes(),
                'autocomplete' => $linkEntity->renderAutocomplete(),
                'preview'      => $linkEntity->renderPreview(),
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
     * @return shopOrder
     */
    public function getEntity()
    {
        return new shopOrder($this->getItemLinkModel()->entity_id);
    }

    public function getExtraData()
    {
        $order = new shopOrder($this->getItemLinkModel()->entity_id);

        $order_data_array = $order->dataArray();
        $order_data_array['contact'] = $order->contact_essentials;
        $order_data_array['state'] = $order['state'];

        return [
            'order'                => $order_data_array,
            'last_action_datetime' => $order->last_action_datetime,
            'link'                 => $this->getLinkUrl(),
        ];
    }
}
