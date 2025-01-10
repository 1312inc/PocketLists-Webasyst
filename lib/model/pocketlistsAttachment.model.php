<?php

/**
 * Class pocketlistsAttachmentModel
 */
class pocketlistsAttachmentModel extends pocketlistsModel
{
    protected $table = 'pocketlists_attachment';


    /**
     * @param $attach_id
     * @return string|null
     * @throws waException
     */
    public static function getUrl($attach_id)
    {
        static $url;
        if (empty($attach_id)) {
            return null;
        } elseif (!isset($url)) {
            $url = wa()->getUrl(true).wa()->getConfig()->getBackendUrl().'/'.pocketlistsHelper::APP_ID.'/download/%s';
        }

        return sprintf($url, $attach_id);
    }

    public function getByField($field, $value = null, $all = false, $limit = false)
    {
        $result = parent::getByField($field, $value, $all, $limit);
        if ($all && $field === 'item_id' || is_array($field) && in_array('item_id', $field)) {
            foreach ($result as &$_attachment) {
                $_attachment['url'] = self::getUrl($_attachment['id']);
            }
            return $result;
        }

        return $result;
    }

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
