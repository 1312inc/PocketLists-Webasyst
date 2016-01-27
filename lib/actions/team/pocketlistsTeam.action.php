<?php

class pocketlistsTeamAction extends waViewAction
{
    public function execute()
    {
        // get all pocketlists users
        // all admin
        $teammates = array();
        $teammates_ids = pocketlistsHelper::getAllPocketListsContacts();
        if ($teammates_ids) {
            $teammates = new waContactsCollection('/id/'.implode(',', $teammates_ids).'/');
            $teammates = $teammates->getContacts('id,name,photo_url,login');

            $im = new pocketlistsItemModel();
            $items_count_names = $im->getAssignedItemsCountAndNames($teammates_ids);
            $last_activities = $im->getContactLastActivity($teammates_ids);
            foreach ($teammates as $tid => $tval) {
                if ($tid != wa()->getUser()->getId()) {
                    $teammates[$tid]['last_activity'] = isset($last_activities[$tid]) ? $last_activities[$tid] : false;
                    $teammates[$tid]['items_info'] = array(
                        'count' => 0,
                        'names' => "",
                        'max_priority' => 0,
                    );
                    if (isset($items_count_names[$tid])) {
                        $teammates[$tid]['items_info'] = array(
                            'count' => count($items_count_names[$tid]['item_names']),
                            'names' => implode(', ', $items_count_names[$tid]['item_names']),
                            'max_priority' => $items_count_names[$tid]['item_max_priority'],
                        );
                    }
                } else {
                    unset($teammates[$tid]);
                }
            }

            usort($teammates, array($this, "compare_last_activity"));

            $selected_teammate = waRequest::get('teammate');
            if ($selected_teammate) {
                $user_model = new waUserModel();
                $id = $user_model->select('id')->where("login = s:0", array($selected_teammate))->limit(1)->fetch(
                );
                $id = $id['id'];
            } else {
                $id = reset($teammates);
                $id = $id['id'];
            }
            $items = $im->getAssignedOrCompletesByContactItems($id);
            $this->view->assign('items', $items[0]);
            $this->view->assign('items_done', $items[1]);
            $this->view->assign('count_done_items', count($items[1]));
            $contact = new waContact($id);
            $this->view->assign('current_teammate', array(
                'name' => $contact->getName(),
                'id' => $contact->getId(),
                'photo_url' => $contact->getPhoto()
            ));
        }

        $this->view->assign('teammates', $teammates);
        $this->view->assign('attachments_path', wa()->getDataUrl('attachments/', true));
    }

    private function compare_last_activity($a, $b)
    {
        return strtotime($b['last_activity']) - strtotime($a['last_activity']);
    }
}
