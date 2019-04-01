<?php

class pocketlistsTeamAction extends pocketlistsViewAction
{
    public function execute()
    {
        // get all pocketlists users
        // all admin
        $teammates = [];
        $teammates_ids = pocketlistsRBAC::getAccessContacts();
        if ($teammates_ids) {
            /** @var pocketlistsContactFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory('Teammate');
            $teammates = $factory->getTeammates($teammates_ids);

            $selected_teammate = waRequest::get('teammate');
            $lists = [];
            if ($selected_teammate) {
                $user_model = new waUserModel();
                $id = $user_model->getByLogin($selected_teammate);
                $id = $id['id'];

                $lm = new pocketlistsListModel();
                $lists = $lm->filterArchive($lm->getTeamLists($id));
                $list_activities = $lm->getLastActivitiesList($id);
                foreach ($lists as $list_id => $list) {
                    $lists[$list_id]['last_contact_ativity'] = 0;
                    if (isset($list_activities[$list_id])) {
                        $lists[$list_id]['last_contact_ativity'] = $list_activities[$list_id]['last_date'];
                    }
                }
                usort($lists, [$this, 'sort_by_activity']);
            } else {
                $id = reset($teammates);
                $id = $id['id'];
            }
            $this->view->assign('lists', $lists);

            $im = new pocketlistsItemModel();
            $items = $im->getAssignedOrCompletesByContactItems($id);
            $this->view->assign('items', $im->getProperSort($im->extendItemData($items[0])));
            $this->view->assign('items_done', $im->extendItemData($items[1]));
            $this->view->assign('count_done_items', count($items[1]));
            $contact = new waContact($id);
            $this->view->assign('current_teammate', pocketlistsHelper::getContactData($contact));
        }

        $this->view->assign('teammates', $teammates);
        $this->view->assign('pl2_attachments_path', wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID));
        $this->view->assign('print', waRequest::get('print', false));
        $this->view->assign('user', $this->user);
    }

    private function sort_by_activity($a, $b)
    {
        return strtotime($a['last_contact_ativity']) < strtotime($b['last_contact_ativity']);
    }
}
