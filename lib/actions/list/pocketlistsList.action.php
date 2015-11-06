<?php

class pocketlistsListAction extends waViewAction
{
    public function execute()
    {
        $list_id = $this->params['list_id'] ? $this->params['list_id'] : waRequest::get(
            'list_id',
            false,
            waRequest::TYPE_INT
        );

        $this->view->assign(
            'list',
            array(
                'id' => $list_id,
                'name' => 'Groceries',
            )
        );

        $this->view->assign(
            'items',
            array(
                array(
                    'id' => 1,
                    'list_id' => $list_id,
                    'left_key_id' => 2,
                    'right_key_id' => 4,
                    'status' => 2,
                    'create_datetime' => date("Y-m-d H:i:s"),
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'name' => 'Food',
                    'note' => 'HJkb kylubnkyvb lbk',
                    'due_date' => date("Y-m-d"),
                    'due_datetime' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                ),
                array(
                    'id' => 2,
                    'list_id' => $list_id,
                    'left_key_id' => 2,
                    'right_key_id' => 4,
                    'status' => 2,
                    'create_datetime' => date("Y-m-d H:i:s"),
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'name' => 'Roast beef',
                    'note' => 'HJkb kylubnkyvb lbk',
                    'due_date' => date("Y-m-d"),
                    'due_datetime' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                ),
                array(
                    'id' => 3,
                    'list_id' => $list_id,
                    'left_key_id' => 2,
                    'right_key_id' => 4,
                    'status' => 2,
                    'create_datetime' => date("Y-m-d H:i:s"),
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'name' => 'Rib eye',
                    'note' => 'HJkb kylubnkyvb lbk',
                    'due_date' => date("Y-m-d"),
                    'due_datetime' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                ),
                array(
                    'id' => 4,
                    'list_id' => $list_id,
                    'left_key_id' => 2,
                    'right_key_id' => 4,
                    'status' => 2,
                    'create_datetime' => date("Y-m-d H:i:s"),
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'name' => 'Drinks',
                    'note' => 'HJkb kylubnkyvb lbk',
                    'due_date' => date("Y-m-d"),
                    'due_datetime' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                ),
                array(
                    'id' => 5,
                    'list_id' => $list_id,
                    'left_key_id' => 2,
                    'right_key_id' => 4,
                    'status' => 2,
                    'create_datetime' => date("Y-m-d H:i:s"),
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'name' => 'Beer',
                    'note' => 'HJkb kylubnkyvb lbk',
                    'due_date' => date("Y-m-d"),
                    'due_datetime' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                ),
                array(
                    'id' => 6,
                    'list_id' => $list_id,
                    'left_key_id' => 2,
                    'right_key_id' => 4,
                    'status' => 2,
                    'create_datetime' => date("Y-m-d H:i:s"),
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'name' => 'Wine',
                    'note' => 'HJkb kylubnkyvb lbk',
                    'due_date' => date("Y-m-d"),
                    'due_datetime' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                ),
            )
        );
    }
}