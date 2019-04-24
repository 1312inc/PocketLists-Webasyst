<?php

/**
 * Class pocketlistsAttachmentModel
 */
class pocketlistsAttachmentModel extends pocketlistsModel
{
    protected $table = 'pocketlists_attachment';

    /**
     * @param array|int $item_ids
     * @param array     $names
     *
     * @return bool|void
     * @throws waException
     */
    public function remove($item_ids = [], $names = [])
    {
        if (empty($names)) { // delete all attchments
            if (!is_array(($item_ids))) {
                $item_ids = [$item_ids];
            }
            if ($this->deleteByField('item_id', $item_ids)) {
                foreach ($item_ids as $item_id) {
                    waFiles::delete(wa()->getDataPath('attachments/'.$item_id.'/', true, pocketlistsHelper::APP_ID));
                }
            }
        } else { // delete item attchments by names
            if (!is_array(($names))) {
                $names = [$names];
            }

            if ($this->deleteByField(
                [
                    'item_id'  => $item_ids,
                    'filename' => $names,
                ]
            )) {
                foreach ($names as $name) {
                    waFiles::delete(
                        wa()->getDataPath('attachments/'.$item_ids.'/'.$name, true, pocketlistsHelper::APP_ID)
                    );
                }
            }
        }

        return true;
    }
}
