<?php

class pocketlistsItemAssignToController extends waJsonController
{
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $team_id = waRequest::post('team_id', 0, waRequest::TYPE_INT);

        if ($id > 0 && $team_id > 0) {
            $im = new pocketlistsItemModel();
            $lm = new pocketlistsListModel();

            $item = $im->getById($id);
            $list = $lm->getById($item['list_id']);
            $contact = new waContact($team_id);

            if ($item && $list && $contact && pocketlistsRBAC::canAccessToList($list['id']) && pocketlistsRBAC::canAccessToList($list['id'],
                    $contact->getId())
            ) {
                // todo: childs??
                $data = array(
                    'assigned_contact_id' => $contact->getId(),
                    'update_datetime'     => date('Y-m-d H:i:s'),

                );
                if ($im->updateById($item['id'], $data)
                ) {
                    $item = array_merge($item, $data);
                    pocketlistsNotifications::notifyAboutNewAssign($item, wa()->getUser()->getName());
                    $this->response = $contact->getName();
                } else {
                    $this->errors = 'db error';
                }
            } else {
                $this->errors = 'no such item or list or contact or access error';
            }
        } else {
            $this->errors = 'no id';
        }
    }
}
