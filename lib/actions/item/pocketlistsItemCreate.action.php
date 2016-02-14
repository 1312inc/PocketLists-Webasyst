<?php

class pocketlistsItemCreateAction extends waViewAction
{
    public function execute()
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);
        $filter = waRequest::post('filter', false);
        $list_id = waRequest::post('list_id', false, waRequest::TYPE_INT);
        $assigned_contact_id = waRequest::post('assigned_contact_id', false, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();
        $inserted = $inserted_items = $items = array();
        $assign_contact = null;
        $user_id = wa()->getUser()->getId();
        if ($assigned_contact_id) {
            $assign_contact = new waContact($assigned_contact_id);
        }

        // if no list id passed - get default list from settings
        if (!$list_id) {
            $us = new pocketlistsUserSettings($assign_contact ? $assign_contact->getId() : $user_id);
            $list_id = $us->getStreamInboxList();
        }
        if ($data) {
            $paste = false;
            if (!is_array($data)) {
                $data = array($data);
            } else {
                $paste = true;
            }
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);
            foreach ($data as $i => $d) {
                $data[$i]['create_datetime'] = date("Y-m-d H:i:s");
                $data[$i]['list_id'] = $list ? $list['id'] : null;
                $data[$i]['contact_id'] = $user_id;

                if ($assigned_contact_id) {
                    $data[$i]['assigned_contact_id'] = $assign_contact->getId();
                }

                if (!empty($data[$i]['due_date'])) {
                    $data[$i]['due_date'] = waDateTime::date('Y-m-d', strtotime($data[$i]['due_date']));
                }

                $data[$i]['name'] = trim($data[$i]['name']);

                // if add throught paste - cut some letters
                if ($paste) {
                    $data[$i]['name'] = preg_replace('/^(([-|—|–]*)(\s*))(.*)$/u', "$4", $data[$i]['name']);
                }

                // natural input parse
                if ($ni = pocketlistsNaturalInput::matchPriority($data[$i]['name'])) {
                    $data[$i]['name'] = $ni['name'];
                    $data[$i]['priority'] = $ni['priority'];
                }
                if ($ni = pocketlistsNaturalInput::matchNote($data[$i]['name'])) {
                    $data[$i]['name'] = $ni['name'];
                    $data[$i]['note'] = $ni['note'];
                }

                $us = new pocketlistsUserSettings($user_id);
                if ($us->getNaturalInput() && $ni = pocketlistsNaturalInput::matchDueDate($data[$i]['name'])) {
                    $data[$i]['due_date'] = $ni['due_date'];
                    $data[$i]['due_datetime'] = $ni['due_datetime'];
                }

                $last_id = $im->insert($data[$i], 1);
                $inserted[] = $last_id;
                $inserted_items[] = $data[$i] + array('id' => $last_id);
            }

            if ($inserted) {
                pocketlistsNotifications::notifyAboutNewItems($inserted_items, $list);

                switch ($filter) {
                    case 'favorites':
                        $ufm = new pocketlistsUserFavoritesModel();
                        foreach ($inserted_items as $item) {
                            $ufm->insert(array(
                                'item_id' => $item['id'],
                                'contact_id' => $user_id
                            ));
                        }
                        break;
                }

                $items = $im->getById($inserted);
                if (isset($items['id'])) {
                    $items = array($items);
                }

                // log this action
                $this->logAction('new_items', $list);
            }
        }

        $this->view->assign('items', $items);
    }
}
