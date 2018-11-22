<?php

/**
 * Class pocketlistsStoreColor
 */
final class pocketlistsStoreColor
{
    const NONE   = 'none';
    const RED    = 'red';
    const GREEN  = 'green';
    const BLUE   = 'blue';
    const YELLOW = 'yellow';
    const PURPLE = 'purple';
    const ORANGE = 'orange';
    const BROWN  = 'brown';

    /**
     * @return array
     */
    public static function getColors()
    {
        return [
            self::NONE   => _w(self::NONE),
            self::YELLOW => _w(self::YELLOW),
            self::GREEN  => _w(self::GREEN),
            self::BLUE   => _w(self::BLUE),
            self::RED    => _w(self::RED),
            self::PURPLE => _w(self::PURPLE),
            //self::BROWN  => _w(self::BROWN),
            //self::ORANGE => _w(self::ORANGE),
        ];
    }
}
