<?php

final class pocketlistsPushSenderService
{
    /** @var pocketlistsOnesignalPush|null */
    private $push_adapter;

    /** @var string|null */
    private $wa_installation_id;

    public function __construct()
    {
        try {
            $one_push_class = 'wa-system/push/adapters/onesignal/onesignalPush.class.php';
            if (!class_exists('onesignalPush') && file_exists(wa()->getConfig()->getRootPath().'/'.$one_push_class)) {
                waAutoload::getInstance()->add('onesignalPush', $one_push_class);
            }
            $this->push_adapter = new pocketlistsOnesignalPush(pl2()->getOption('onesignal_app_id'));
        } catch (waException $e) {
            pocketlistsLogger::debug('Unable to load wa-system/push/adapters/onesignal/onesignalPush.class.php');
        }

        $waid_credentials = (new waAppSettingsModel())->get('webasyst', 'waid_credentials');
        $waid_credentials = json_decode($waid_credentials, true);
        $this->wa_installation_id = ifempty($waid_credentials, 'client_id', null);
    }

    /**
     * @param string $type
     * @param array $item
     * @param array $log_item
     * @param waContact $to_contact
     * @param array $badge_counts
     * @return void
     * @throws waException
     */
    public function send($type, $item, $log_item, $to_contact, $badge_counts = null)
    {
        pocketlistsLogger::debug(
            sprintf(
                'Start to send push notifications about task "%s" to contact %s (%s)',
                $item['name'] ?? '',
                $to_contact->getName(),
                $to_contact->getId()
            )
        );

        if (!$this->push_adapter || !$this->push_adapter->isEnabled()) {
            pocketlistsLogger::debug('Push adapter is not initialized or is not enables');

            return;
        }

        $log_has_text = !!strlen(trim(ifset($log_item, 'text', '')));

        $data = [
            'title'     => $this->getTitle($type, $item),
            'message'   => $this->getMessage($type, $item, $to_contact, $log_item),
            'url'       => null,
            'image_url' => null,
            'data'      => [
                'task_id'    => $item['id'],
                'comment_id' => $log_has_text ? $log_item['id'] : null,
                'type'       => $type,
                'webasyst_installation_id' => $this->wa_installation_id,
                'group_name' => sprintf(_w('%d %s'), $item['id'], $item['name']),
                'group_id'   => sprintf('%s-%s', $this->wa_installation_id, $item['id']),
                'badge'      => $badge_counts,
            ]
        ];

        pocketlistsLogger::debug($data);

        $this->push_adapter->sendByContact($to_contact->getId(), $data);
    }

    /**
     * @param string $type
     * @param array $item
     * @param waContact $to
     * @param array $log
     * @return string
     * @throws waException
     */
    private function getMessage($type, $item, $to, $log)
    {
        switch ($type) {
            default:
                return sprintf('User %s sent a new item to user %s', wa()->getUser()->getName(), 'contact with id = ');
        }
    }

    /**
     * @param $str
     * @return string
     */
    private function prepare($str)
    {
        return trim(preg_replace("/\n+/mu", '', preg_replace('/\s+/mu', ' ', $str)));
    }

    /**
     * @param string $type
     * @param $item
     * @return string
     */
    private function getTitle($type, $item)
    {
        switch ($type) {
            default:
                return sprintf('⚡ '._w('%s %s'), $item['id'], $item['name']);
        }
    }
}
