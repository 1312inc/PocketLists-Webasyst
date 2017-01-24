<?php

class pocketlistsTeamAction extends waViewAction
{
    public function execute()
    {
        // get all pocketlists users
        // all admin
        $teammates = array();
        $teammates_ids = pocketlistsHelper::getAllListsContacts();
        if ($teammates_ids) {
            $teammates = pocketlistsHelper::getTeammates($teammates_ids);

            $selected_teammate = waRequest::get('teammate');
            if ($selected_teammate) {
                $user_model = new waUserModel();
                $id = $user_model->getByLogin($selected_teammate);
                $id = $id['id'];
            } else {
                $id = reset($teammates);
                $id = $id['id'];
            }

            $im = new pocketlistsItemModel();
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
}
