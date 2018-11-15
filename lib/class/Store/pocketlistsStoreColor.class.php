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

    /**
     * @return array
     */
    public static function getColors()
    {
        return [
            self::NONE   => _w(self::NONE),
            self::RED    => _w(self::RED),
            self::GREEN  => _w(self::GREEN),
            self::BLUE   => _w(self::BLUE),
            self::YELLOW => _w(self::YELLOW),
            self::PURPLE => _w(self::PURPLE),
        ];
    }
}
