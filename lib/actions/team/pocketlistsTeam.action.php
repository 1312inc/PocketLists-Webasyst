<?php

class pocketlistsTeamAction extends  waViewAction
{
    public function execute()
    {
        // get all pocketlists users
        // all admin

        $teammates_ids = array_keys(pocketlistsHelper::getAllPocketListsContacts());
        $teammates = new waContactsCollection('/id/'.implode(',', $teammates_ids).'/');
        $teammates = $teammates->getContacts(array('id', 'name', 'photo_url'));

        $im = new pocketlistsItemModel();
        $items_count_names = $im->getAssignedItemsCountAndNames($teammates_ids);
        foreach ($teammates as $tid => $tval) {
            $teammates[$tid]['item_count'] = isset($items_count_names[$tid]) ? count($items_count_names[$tid]) : false;
            $teammates[$tid]['item_names'] = isset($items_count_names[$tid]) ? implode(', ', $items_count_names[$tid]) : false;
        }

        $this->view->assign('teammates', $teammates);
    }
}