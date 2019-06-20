<?php

/**
 * Class pocketlistsPocketModel
 */
class pocketlistsPocketModel extends pocketlistsModel
{
    protected $table = 'pocketlists_pocket';

    /**
     * @param bool $contact_id
     *
     * @return array|null
     * @throws waException
     */
    public function getAllPockets($contact_id = false)
    {
        $where_ids = '';
        $accessed_pockets = [];
        if ($contact_id && ($accessed_pockets = pocketlistsRBAC::getAccessPocketForContact($contact_id))) {
            $where_ids = 'WHERE id IN (i:access_id)';
        }

        $sql = "SELECT * FROM {$this->table} {$where_ids} ORDER BY sort ASC ";

        $q = $this->query(
            $sql,
            [
                'access_id' => $accessed_pockets,
            ]
        );

        return $q->fetchAll();
    }

    /**
     * @param $id
     *
     * @return bool|waDbResultDelete|waDbResultInsert|waDbResultReplace|waDbResultSelect|waDbResultUpdate
     * @throws waDbException
     * @throws waException
     */
    public function deleteAll($id)
    {
        if (!$id) {
            return false;
        }

        // delete attachments
        $im = new pocketlistsItemModel();
        $items = $im->getAllByPocket($id);
        $am = new pocketlistsAttachmentModel();
        $am->remove(array_keys($items));

        $q = "DELETE p, l, i
              FROM {$this->table} p
              LEFT JOIN pocketlists_list l ON l.pocket_id = p.id
              LEFT JOIN pocketlists_list_sort ls ON ls.list_id = l.id
              LEFT JOIN pocketlists_item i ON i.list_id = l.id OR i.key_list_id = l.id
              LEFT JOIN pocketlists_user_favorites uf ON uf.item_id = i.id
              LEFT JOIN pocketlists_attachment a ON a.item_id = i.id
              LEFT JOIN pocketlists_comment c ON c.item_id = i.id
              LEFT JOIN pocketlists_item_tags it ON it.item_id = i.id
              WHERE p.id = i:pocket_id";

        return $this->query($q, ['pocket_id' => $id]);
    }

    /**
     * @param int $pocketId
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countLists($pocketId)
    {
        $accessedLists = pocketlistsRBAC::getAccessListForContact();

        $listsSql = '';
        if ($accessedLists) {
            $listsSql = 'AND id IN (i:lists)';
        }

        return (int)$this
            ->query(
                "SELECT COUNT(*) count_lists FROM pocketlists_list WHERE pocket_id = i:pocket_id AND archived = 0 {$listsSql}",
                ['pocket_id' => $pocketId, 'lists' => $accessedLists]
            )
            ->fetchField('count_lists');
    }

    /**
     * @param $listId
     *
     * @return array
     */
    public function getByListId($listId)
    {
        $sql = "SELECT * FROM {$this->table} p JOIN pocketlists_list pl on pl.pocket_id = p.id AND pl.id = i:list_id";

        $pocket = $this->query($sql, ['list_id' => $listId])->fetchAll();

        return reset($pocket);
    }
}
