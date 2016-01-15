<?php

class pocketlistsAttachmentModel extends waModel
{
    protected $table = 'pocketlists_attachment';

    /**
     * Deletes item attchments from DB and FS
     * @param $item_ids int|array
     * @param $names array
     */
    public function delete($item_ids, $names = array())
    {
        if (empty($names)) { // delete all attchments
            if (!is_array(($item_ids))) {
                $item_ids = array($item_ids);
            }
            $this->deleteByField('item_id', $item_ids);
            foreach ($item_ids as $item_id) {
                waFiles::delete(wa()->getDataPath('attachments/' . $item_id . '/', true));
            }
        } else { // delete item attchments by names
            if (!is_array(($names))) {
                $names = array($names);
            }

            $this->deleteByField(array(
                'item_id' => $item_ids,
                'filename' => $names
            ));
            foreach ($names as $name) {
                waFiles::delete(wa()->getDataPath('attachments/' . $item_ids . '/' . $name, true));
            }
        }

    }
}
