<?php

class pocketlistsListAction extends waViewAction
{
    public function execute()
    {
        $list_id = waRequest::get('id', false, waRequest::TYPE_INT);

        if ($list_id === -1) { // new list
            $this->view->assign('new', true);
        } else { // existing list
            $lm = new pocketlistsListModel();
            $this->view->assign('list', $lm->getList($list_id));

            $im = new pocketlistsItemModel();
            $this->view->assign('items', $im->getByList($list_id));

//            $this->view->assign(
//                'items',
//                array(
//                    array(
//                        'id' => 1,
//                        'list_id' => $list_id,
//                        'left_key_id' => 2,
//                        'right_key_id' => 4,
//                        'status' => 2,
//                        'create_datetime' => date("Y-m-d H:i:s"),
//                        'update_datetime' => date("Y-m-d H:i:s"),
//                        'name' => 'Food',
//                        'note' => 'HJkb kylubnkyvb lbk',
//                        'due_date' => date("Y-m-d"),
//                        'due_datetime' => date("Y-m-d H:i:s"),
//                        'amount' => 0,
//                    ),
//                    array(
//                        'id' => 2,
//                        'list_id' => $list_id,
//                        'left_key_id' => 2,
//                        'right_key_id' => 4,
//                        'status' => 2,
//                        'create_datetime' => date("Y-m-d H:i:s"),
//                        'update_datetime' => date("Y-m-d H:i:s"),
//                        'name' => 'Roast beef',
//                        'note' => 'HJkb kylubnkyvb lbk',
//                        'due_date' => date("Y-m-d"),
//                        'due_datetime' => date("Y-m-d H:i:s"),
//                        'amount' => 0,
//                    ),
//                    array(
//                        'id' => 3,
//                        'list_id' => $list_id,
//                        'left_key_id' => 2,
//                        'right_key_id' => 4,
//                        'status' => 2,
//                        'create_datetime' => date("Y-m-d H:i:s"),
//                        'update_datetime' => date("Y-m-d H:i:s"),
//                        'name' => 'Rib eye',
//                        'note' => 'HJkb kylubnkyvb lbk',
//                        'due_date' => date("Y-m-d"),
//                        'due_datetime' => date("Y-m-d H:i:s"),
//                        'amount' => 0,
//                    ),
//                    array(
//                        'id' => 4,
//                        'list_id' => $list_id,
//                        'left_key_id' => 2,
//                        'right_key_id' => 4,
//                        'status' => 2,
//                        'create_datetime' => date("Y-m-d H:i:s"),
//                        'update_datetime' => date("Y-m-d H:i:s"),
//                        'name' => 'Drinks',
//                        'note' => 'HJkb kylubnkyvb lbk',
//                        'due_date' => date("Y-m-d"),
//                        'due_datetime' => date("Y-m-d H:i:s"),
//                        'amount' => 0,
//                    ),
//                    array(
//                        'id' => 5,
//                        'list_id' => $list_id,
//                        'left_key_id' => 2,
//                        'right_key_id' => 4,
//                        'status' => 2,
//                        'create_datetime' => date("Y-m-d H:i:s"),
//                        'update_datetime' => date("Y-m-d H:i:s"),
//                        'name' => 'Beer',
//                        'note' => 'HJkb kylubnkyvb lbk',
//                        'due_date' => date("Y-m-d"),
//                        'due_datetime' => date("Y-m-d H:i:s"),
//                        'amount' => 0,
//                    ),
//                    array(
//                        'id' => 6,
//                        'list_id' => $list_id,
//                        'left_key_id' => 2,
//                        'right_key_id' => 4,
//                        'status' => 2,
//                        'create_datetime' => date("Y-m-d H:i:s"),
//                        'update_datetime' => date("Y-m-d H:i:s"),
//                        'name' => 'Wine',
//                        'note' => 'HJkb kylubnkyvb lbk',
//                        'due_date' => date("Y-m-d"),
//                        'due_datetime' => date("Y-m-d H:i:s"),
//                        'amount' => 0,
//                    ),
//                )
//            );
        }
    }
}