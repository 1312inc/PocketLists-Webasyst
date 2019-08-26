<?php

/**
 * Class pocketlistsProPluginShortcutModel
 */
class pocketlistsProPluginShortcutModel extends pocketlistsModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_pro_shortcut';

    /**
     * @return array
     */
    public function getAllGrouped()
    {
        return $this
            ->select('*')
            ->order('`group` ASC, id ASC')
            ->fetchAll('group', 2);
    }

    /**
     * @param int $group
     *
     * @return array|null
     * @throws waException
     */
    public function getByGroup($group)
    {
        return $this
            ->select('*')
            ->where('`group` = i:id', ['id' => $group])
            ->order('id ASC')
            ->fetchAll();
    }

    /**
     * @param string $term
     * @param int    $limit
     *
     * @return array
     */
    public function getByTerm($term, $limit = 10)
    {
        $term = $this->escape($term, 'like');

        return $this
            ->select('*')
            ->where("name like '$term%'")
            ->limit(sprintf('%d, %d', 0, $limit))
            ->order('id ASC')
            ->fetchAll();
    }

    /**
     * @param string $name
     * @param int    $group
     *
     * @return bool
     */
    public function deleteByNameFromGroup($name, $group)
    {
        return $this->deleteByField(
            [
                'name'  => $name,
                'group' => $group,
            ]
        );
    }

    /**
     * @return int
     */
    public function getMaxGroup()
    {
        return (int)$this->select('max(`group`) as maxgroup')->fetchField();
    }

    public function renewGroupNums()
    {
        $i = 1;
        foreach ($this->getAllGrouped() as $groupNum => $group) {
            if ($groupNum) {
                $this->updateByField('group', $groupNum, ['group' => $i++]);
            }
        }
    }
}
