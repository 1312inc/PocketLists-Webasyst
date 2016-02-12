<?php

class pocketlistsListModel extends waModel
{
    protected $table = 'pocketlists_list';

    public function getById($id)
    {
        return $this->query(
            "SELECT
              i.*,
              l.*,
              uf.contact_id favorite
            FROM {$this->table} l
            LEFT JOIN pocketlists_item i ON i.key_list_id = l.id
            LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
            WHERE l.id = i:id",
            array('id' => $id, 'contact_id' => wa()->getUser()->getId())
        )->fetchAssoc();
    }

    public function add($data, $type = false)
    {
        $inserted_list_id = $this->insert($data, $type);
        $data['key_list_id'] = $inserted_list_id;
        $im = new pocketlistsItemModel();
        $inserted_item_id = $im->insert($data, $type);
        $data['id'] = $inserted_list_id;
        $this->updateById($data['id'], array('key_item_id' => $inserted_item_id));

        return $inserted_list_id ? $data : false;
    }

    public function update($id, $data)
    {
        $im = new pocketlistsItemModel();
        unset($data['id']);
        $item = $im->getByField('key_list_id', $id);
        if ($im->updateWithCalcPriority($item['id'], array_merge($item, $data)) &&
            $this->updateById($id, $data)
        ) {
            $list = $this->getById($id);
            return $list;
        }
        return false;
    }

    public function delete($id)
    {
        $im = new pocketlistsItemModel();
        $items = $im->getAllByList($id);
        $items_list = $im->getByField('key_list_id', $id, true);

        $im->deleteByField('list_id', $id);
        $im->deleteByField('key_list_id', $id);

        $am = new pocketlistsAttachmentModel();
        $am->delete(array_keys($items)); // items attachements
        $items = array();
        foreach ($items_list as $item) {
            $items[] = $item['id'];
        }
        $am->delete($items); // list attachements

        return $this->deleteById($id);
    }

    public function getLists($pocket_id = false)
    {
        $select_pocket = "";
        if ($pocket_id) {
            $select_pocket = " AND l.pocket_id = i:pocket_id";
        }
        $sql = "SELECT
                  i2.*,
                  l.*,
                  SUM(IF(i.list_id IS NULL, 0, 1)) 'count',
                  MAX(i.priority) 'max_priority',
                  MIN(i.due_date) 'min_due_date',
                  MIN(i.due_datetime) 'min_due_datetime'
                FROM {$this->table} l
                LEFT JOIN pocketlists_item i ON i.list_id = l.id AND i.status = 0
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = l.id
                WHERE
                  l.archived = i:archived
                  {$select_pocket}
                GROUP BY l.id
                ORDER BY l.sort";

        $lists = $this->query(
            $sql,
            array(
                'archived' => 0,
                'pocket_id' => $pocket_id
            )
        )->fetchAll();
        foreach ($lists as $id => $list) {
            $lists[$id]['calc_priority'] = max(pocketlistsHelper::calcPriorityOnDueDate($list['min_due_date'], $list['min_due_datetime']), $list['max_priority']);
        }
        return $lists;
    }

    public function getArchived()
    {
        $available_pockets = pocketlistsHelper::getAccessPocketForContact();

        $sql = "SELECT
                  i2.*,
                  l.*,
                  SUM(IF(i.list_id IS NULL, 0, 1)) 'count',
                  MAX(i.priority) 'max_priority',
                  MIN(i.due_date) 'min_due_date',
                  MIN(i.due_datetime) 'min_due_datetime'
                FROM pocketlists_list l
                LEFT JOIN pocketlists_item i ON i.list_id = l.id AND i.status = 0
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = l.id
                WHERE
                  l.archived = i:archived
                  AND l.pocket_id IN (i:pocket_ids)
                GROUP BY l.id";

        $lists = $this->query(
            $sql,
            array(
                'archived' => 1,
                'pocket_ids' => $available_pockets
            )
        )->fetchAll();
        return $lists;
    }
}
