<?php

class pocketlistsItemModel extends waModel
{
    protected $table = 'pocketlists_item';

    public function getByList($list_id, $tree = true, $root = 0)
    {
        $items = $this->getByField('list_id', $list_id, true);
        return $tree ? $this->getTree($items, $root) : $items;
    }

    private function getTree($items, $root_item)
    {
        $result = array();
        foreach ($items as $id => $item) {
            $result[$item['id']] = $item;
            $result[$item['id']]['childs'] = array();
        }

        foreach ($result as $id => $item) {
            $result[$item['parent_id']]['childs'][$id] =& $result[$id];
        }
        return $result[$root_item]['childs'];
    }
}