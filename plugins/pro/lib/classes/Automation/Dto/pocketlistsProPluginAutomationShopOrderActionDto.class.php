<?php

/**
 * Class pocketlistsProPluginAutomationShopOrderActionDto
 */
class pocketlistsProPluginAutomationShopOrderActionDto
{
    /**
     * @var shopOrder
     */
    public $order;

    /**
     * @var int
     */
    public $actionPerformerContactId;

    /**
     * pocketlistsProPluginAutomationShopOrderActionDto constructor.
     *
     * @param shopOrder $order
     * @param int|null  $actionPerformerContactId
     */
    public function __construct(shopOrder $order, $actionPerformerContactId)
    {
        $this->order = $order;
        $this->actionPerformerContactId = $actionPerformerContactId;
    }
}
