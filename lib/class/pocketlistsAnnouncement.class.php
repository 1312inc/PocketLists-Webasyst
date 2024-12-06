<?php

class pocketlistsAnnouncement
{

    public static function addAnnouncements($announcements)
    {
        $result = [];
        foreach ($announcements as $_announcement) {
            $contact_id = ifset($_announcement, 'contact_id', wa()->getUser()->getId());
            $access = ifset($_announcement, 'access', 'limited');
            $result[] = [
                'app_id'       => pocketlistsHelper::APP_ID,
                'type'         => ifset($_announcement, 'type', null),
                'contact_id'   => $contact_id,
                'text'         => ifset($_announcement, 'text', ''),
                'data'         => ifset($_announcement, 'data', null),
                'datetime'     => ifset($_announcement, 'datetime', date('Y-m-d H:i:s')),
                'ttl_datetime' => ifset($_announcement, 'ttl_datetime', null),
                'is_pinned'    => ifset($_announcement, 'is_pinned', 0),
                'access'       => $access,

                /* for pocketlists_item_link */
                'item_id'     => ifset($_announcement, 'id', null),
                'entity_id'   => null,
                'app'         => 'webasyst',
                'entity_type' => 'announcement'
            ];
            if ($access === 'limited') {
                /* for wa_announcement_rights */
                $rights[] = [
                    'group_id'        => -1 * $contact_id,
                    'announcement_id' => null
                ];
            }
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
                                'group_id'        => -1 * $_result['contact_id'],
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
}
