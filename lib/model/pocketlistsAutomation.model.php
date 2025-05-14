<?php

/**
 * Class pocketlistsAutomationModel
 */
class pocketlistsAutomationModel extends pocketlistsModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_pro_automation';

    /**
     * @param string $name
     * @param string $type
     *
     * @return array
     */
    public function getByEventNameAndType($name, $type)
    {
        return $this->select('*')->where('event = ? and type = ?', $name, $type)->fetchAll();
    }
}
