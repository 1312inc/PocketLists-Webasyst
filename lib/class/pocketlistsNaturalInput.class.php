<?php

class pocketlistsNaturalInput
{
    private static $instance;
    private $json = array();

    public function __construct()
    {
        $files_path = wa()->getAppPath().'data/natural_input/';
        $files = array(
            $files_path.'natural-input-en.json',
            $files_path.'natural-input-ru.json'
        );
        foreach ($files as $file) {
            if (file_exists($file)) {
                $this->json[] = json_decode(file_get_contents($file));
            }
        }
    }

    /**
     *
     * @return pocketlistsNaturalInput
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function matchCategory($category_name)
    {
        $instance = self::getInstance();
        return false;
    }
}