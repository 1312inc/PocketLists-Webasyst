<?php

/** @deprecated  */
class pocketlistsPocketModel extends waModel
{
    protected $table = 'pocketlists_pocket';

    /**
     * @param $contact_id int
     * @return array
     */
    public function getAllPockets($contact_id = false)
    {
        $where_ids = '';
        $accessed_pockets = array();
        if ($contact_id) {
            $accessed_pockets = pocketlistsHelper::getAccessPocketForContact($contact_id);
            $where_ids = 'WHERE id IN (i:access_id)';
        }

        $sql = "SELECT * FROM {$this->table} {$where_ids} ORDER BY sort ASC ";
        return $this->query($sql, array(
            'access_id' => $accessed_pockets
        ))->fetchAll();
    }

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
        $am->delete(array_keys($items));

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

        return $this->query($q, array('pocket_id' => $id));
    }
}
