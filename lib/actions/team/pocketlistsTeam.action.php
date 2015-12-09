<?php

class pocketlistsTeamAction extends  waViewAction
{
    public function execute()
    {
        // get all pocketlists users
        // all admin
        $teammates = array();
        $teammates_ids = pocketlistsHelper::getAllPocketListsContacts();
        if ($teammates_ids) {
            $teammates = new waContactsCollection('/id/'.implode(',', $teammates_ids).'/');
            $teammates = $teammates->getContacts(array('id', 'name', 'photo_url', 'login'));

            $im = new pocketlistsItemModel();
            $items_count_names = $im->getAssignedItemsCountAndNames($teammates_ids);
            $last_activities = $im->getContactLastActivity($teammates_ids);
            foreach ($teammates as $tid => $tval) {
                if ($tid != wa()->getUser()->getId()) {
                    $teammates[$tid]['item_count'] = isset($items_count_names[$tid]) ? count(
                        $items_count_names[$tid]
                    ) : false;
                    $teammates[$tid]['item_names'] = isset($items_count_names[$tid]) ? implode(
                        ', ',
                        $items_count_names[$tid]
                    ) : false;
                    $teammates[$tid]['last_activity'] = isset($last_activities[$tid]) ? $last_activities[$tid]: false;
                } else {
                    unset($teammates[$tid]);
                }
            }
        }

        $this->view->assign('teammates', $teammates);
    }
}