<?php

/**
 * Class pocketlistsProPluginShortcutFactory
 *
 * @method pocketlistsProPluginShortcutModel getModel()
 */
class pocketlistsProPluginShortcutFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsProPluginShortcut';

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
     * @return pocketlistsProPluginShortcut[]
     * @throws waException
     */
    public function findByGroup($group)
    {
        $shortcuts = $this->getModel()->getByGroup($group);

        return $this->generateWithData($shortcuts, true);
    }
}
