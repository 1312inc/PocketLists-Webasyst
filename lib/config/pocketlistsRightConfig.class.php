<?php

class pocketlistsRightConfig extends waRightConfig
{
    const RIGHT_NONE = 0;
    const RIGHT_FULL = 1;

    public function init()
    {
        $pm = new pocketlistsPocketModel();
        $items = array();
        foreach ($pm->getAllPockets() as $pocket) {
            $items[$pocket['id']] = $pocket['name'];
        }
        $this->addItem(
            'pocket',
            _w('Pocketlists'),
            'selectlist',
            array(
                'items' => $items,
                'position' => 'right',
                'options' => array(
                    self::RIGHT_NONE => _w('No access'),
                    self::RIGHT_FULL => _w('Full access'),
                ),
            )
        );
    }

//    /**
//     * Return custom access rights managed by app for contact id (not considering group he's in) or a set of group ids.
//     * Application must override this if it uses custom access rights storage.
//     *
//     * @param int|array $contact_id contact_id (positive) or a list of group_ids (positive)
//     * @return array access_key => value; for group_ids aggregate status is returned, as if for a member of all groups.
//     */
//    public function getRights($contact_id)
//    {
//        $ppm = new pocketlistsPocketRightsModel();
//        $rights = $ppm->getByField('contact_id', $contact_id);
//        $return = array();
//        foreach($rights as $right) {
//            $return['pocket.'.$rights['pocket_id']] = $right['contact_id'];
//        }
//        return $return;
//    }
//
//    /**
//     * Update custom rights storage for given contact and access_key setting given value.
//     *
//     * @param int $contact_id contact_id (if positive) or group id (if negative)
//     * @param string $right access_key to set value for
//     * @param mixed $value value to save
//     * @return boolean false to write this key and value to system storage; true if application chooses to keep it in its own place.
//     */
    public function setRights($contact_id, $right, $value = null)
    {
        $ppm = new pocketlistsPocketRightsModel();
        if ($right === 'backend') {
            if ($value == 0) { // no access
                $ppm->deleteByField(
                    array(
                        'contact_id' => $contact_id,
                    )
                );
            } else {
                $ppm->insert(
                    array(
                        'pocket_id' => 0,
                        'contact_id' => $contact_id,
                        'right' => $value,
                    ),
                    1
                );
            }
        } else {
            $pocket_id = explode(".", $right);
            if ($value) {
                $ppm->insert(
                    array(
                        'pocket_id' => $pocket_id[1],
                        'contact_id' => $contact_id,
                        'right' => $value,
                    ),
                    1
                );
            } else {
                $ppm->deleteByField(array(
                    'pocket_id' => $pocket_id[1],
                    'contact_id' => $contact_id,
                ));
            }
        }

        return true;
    }
}