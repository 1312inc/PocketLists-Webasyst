<?php

/**
 * Class pocketlistsShortcutFactory
 *
 * @method pocketlistsShortcutModel getModel()
 */
class pocketlistsShortcutFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsShortcut';

    /**
     * @return array
     * @throws waException
     */
    public function findAllGrouped()
    {
        $data = $this->getModel()->getAllGrouped();

        $return = [];
        foreach ($data as $group => $shortcuts) {
            $return[$group] = $this->generateWithData($shortcuts, true);
        }

        return $return;
    }

    /**
     * @param $group
     *
     * @return pocketlistsShortcut[]
     * @throws waException
     */
    public function findByGroup($group)
    {
        $shortcuts = $this->getModel()->getByGroup($group);

        return $this->generateWithData($shortcuts, true);
    }
}
