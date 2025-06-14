<?php

class pocketlistsAnnouncement
{
    const APP = 'webasyst';
    const ENTITY = 'announcement';
    const TTL_DAYS = 30;

    public static function addAnnouncements($announcements)
    {
        $result = [];
        foreach ($announcements as $_announcement) {
            $access = ifset($_announcement, 'access', 'limited');
            $datetime = ifset($_announcement, 'datetime', date('Y-m-d H:i:s'));
            $ttl_datetime = ifset($_announcement, 'ttl_datetime', null);
            if (empty($ttl_datetime)) {
                $ttl_datetime = date('Y-m-d H:i:s', strtotime("$datetime +".self::TTL_DAYS.' days'));
            }
            $result[] = [
                'app_id'           => pocketlistsHelper::APP_ID,
                'type'             => ifset($_announcement, 'type', null),
                'contact_id'       => null,
                'text'             => ifset($_announcement, 'text', ''),
                'data'             => ifset($_announcement, 'data', null),
                'datetime'         => $datetime,
                'ttl_datetime'     => $ttl_datetime,
                'is_pinned'        => ifset($_announcement, 'is_pinned', 0),
                'access'           => $access,
                'right_contact_id' => ifset($_announcement, 'contact_id', wa()->getUser()->getId()),

                /* for pocketlists_item_link */
                'item_id'          => ifset($_announcement, 'id', null),
                'entity_id'        => null,
                'app'              => self::APP,
                'entity_type'      => self::ENTITY
            ];
        }

        if ($result) {
            $wa_model = new waAnnouncementModel();
            $resp = $wa_model->multipleInsert($result);
            if ($resp && $resp->getResult()) {
                $rights = [];
                $last_id = $resp->lastInsertId();
                $rows_count = $resp->affectedRows();
                if ($rows_count === count($result)) {
                    foreach ($result as &$_result) {
                        $_result['entity_id'] = $last_id++;
                        if ($_result['access'] === 'limited') {
                            $rights[] = [
                                'group_id'        => -1 * $_result['right_contact_id'],
                                'announcement_id' => $_result['entity_id']
                            ];
                        }
                    }
                }
                $plil_miodel = pl2()->getModel(pocketlistsItemLink::class);
                $plil_miodel->multipleInsert($result);
                if ($rights) {
                    $war_model = new waAnnouncementRightsModel();
                    $war_model->multipleInsert($rights);
                }
            }
        }
    }

    public static function removeAnnouncements($item_remove_ids)
    {
        $pil_model = pl2()->getModel(pocketlistsItemLink::class);
        $links = $pil_model->getByField([
            'app'         => self::APP,
            'entity_type' => self::ENTITY,
            'item_id'     => $item_remove_ids
        ], true);

        $announcement_ids = array_column($links, 'entity_id');
        if ($announcement_ids) {
            $war_model = new waAnnouncementRightsModel();
            $war_model->deleteByField('announcement_id', $announcement_ids);

            $wa_model = new waAnnouncementModel();
            $wa_model->deleteById($announcement_ids);
        }
    }
}
