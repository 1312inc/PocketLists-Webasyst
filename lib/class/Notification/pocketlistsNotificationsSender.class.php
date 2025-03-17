<?php

class pocketlistsNotificationsSender
{
    public const ITEM_NEW = 'new';

    protected $item;
    protected $action;

    /** @var pocketlistsPushSenderService */
    private $push_sender;

    // in sort of priority, don't change it
    protected $available_actions = [
        self::ITEM_NEW,
    ];

    public function __construct($item, $action = null, $options = [])
    {
        $this->action = $action;

        if (is_scalar($item)) {
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $item = $item_model->getById($item);
        }
        $this->item = $item;

        $this->push_sender = new pocketlistsPushSenderService();
    }

    /**
     * @return void
     * @throws waException
     */
    public function send()
    {
        $this->sendOne($this->action, $this->item['assigned_contact_id']);
    }

    /**
     * @param $type
     * @param $to_contact_id
     * @param $badge_counts
     * @return void
     * @throws waException
     */
    public function sendOne($type, $to_contact_id, $badge_counts = null)
    {
        if ($to_contact_id == wa()->getUser()->getId()) {
            return;
        }

        $to = new waContact($to_contact_id);
        $old_locale = wa()->getLocale();
        try {
            wa()->setLocale($to->getLocale());
            $this->push_sender->send($type, $this->item, [], $to, $badge_counts);
        } catch (Exception $exception) {
            tasksLogger::error($exception);
        }
        wa()->setLocale($old_locale);
    }
}
