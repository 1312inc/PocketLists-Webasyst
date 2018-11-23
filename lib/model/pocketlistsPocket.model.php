<?php

/**
 * Class pocketlistsPocketModel
 *
 * @property int    $sort
 * @property string $name
 * @property string $color
 * @property string $passcode
 */
class pocketlistsPocketModel extends kmModelExt
{
    protected $table = 'pocketlists_pocket';

    /**
     * @param bool|int $contact_id
     *
     * @return null|pocketlistsPocketModel|pocketlistsPocketModel[]
     */
    public function getAllPockets($contact_id = false)
    {
        $where_ids = '';
        $accessed_pockets = [];
//        if ($contact_id) {
//            $accessed_pockets = pocketlistsHelper::getAccessPocketForContact($contact_id);
//            $where_ids = 'WHERE id IN (i:access_id)';
//        }

        $sql = "SELECT * FROM {$this->table} {$where_ids} ORDER BY sort ASC ";

        $q = $this->query(
            $sql,
            [
                'access_id' => $accessed_pockets,
            ]
        );

        return $this->findByQuery($q, false);
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

        // delete from wa_contact_rights
        $wcr = new waContactRightsModel();
        $wcr->deleteByField('name', 'pocket.'.$id);

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
     * @return int
     */
    public function countLists()
    {
        return (int)$this
            ->query(
                "SELECT COUNT(*) count_lists FROM pocketlists_list WHERE pocket_id = i:pocket_id AND archived = 0",
                ['pocket_id' => $this->pk]
            )
            ->fetchField('count_lists');
    }
}
