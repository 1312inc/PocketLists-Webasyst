<?php

/**
 * Class pocketlistsViewHelper
 */
class pocketlistsViewHelper
{
    public const CSS_CLASS_PRIORITY = 'priority';
    public const CSS_CLASS_DUE_DATETIME    = 'due-datetime';
    public const CSS_CLASS_DUE_DATE       = 'due-date';
    public const CSS_CLASS_LIST_INDICATOR = 'list-indicator';

    /**
     * @param int    $priority
     * @param string $type
     *
     * @return mixed|string
     */
    public static function getPriorityCssClass($priority = 0, $type = self::CSS_CLASS_PRIORITY)
    {
        $classes = [
            self::CSS_CLASS_PRIORITY => [
                pocketlistsItem::PRIORITY_BURNINHELL => 'pl-priority-fire',
                pocketlistsItem::PRIORITY_BLACK      => 'pl-priority-black',
                pocketlistsItem::PRIORITY_RED        => 'pl-priority-red',
                pocketlistsItem::PRIORITY_YELLOW     => 'pl-priority-yellow',
                pocketlistsItem::PRIORITY_GREEN      => 'pl-priority-green',
                pocketlistsItem::PRIORITY_NORM       => '',
            ],

            self::CSS_CLASS_DUE_DATETIME => [
                pocketlistsItem::PRIORITY_BURNINHELL => 'pl-due-overdue',
                pocketlistsItem::PRIORITY_BLACK      => 'pl-due-overdue',
                pocketlistsItem::PRIORITY_RED        => 'pl-due-overdue',
                pocketlistsItem::PRIORITY_YELLOW     => 'pl-due-today',
                pocketlistsItem::PRIORITY_GREEN      => 'pl-due-tomorrow',
                pocketlistsItem::PRIORITY_NORM       => '',
            ],

            self::CSS_CLASS_DUE_DATE => [
                pocketlistsItem::PRIORITY_BURNINHELL => 'pl-due-overdue',
                pocketlistsItem::PRIORITY_BLACK      => 'pl-due-overdue',
                pocketlistsItem::PRIORITY_RED        => 'pl-due-overdue',
                pocketlistsItem::PRIORITY_YELLOW     => 'pl-due-today',
                pocketlistsItem::PRIORITY_GREEN      => 'pl-due-tomorrow',
                pocketlistsItem::PRIORITY_NORM       => 'pl-due-someday',
            ],

            self::CSS_CLASS_LIST_INDICATOR => [
                pocketlistsItem::PRIORITY_BURNINHELL => 'indicator onfire',
                pocketlistsItem::PRIORITY_BLACK      => 'indicator black',
                pocketlistsItem::PRIORITY_RED        => 'indicator red',
                pocketlistsItem::PRIORITY_YELLOW     => 'indicator yellow',
                pocketlistsItem::PRIORITY_GREEN      => 'indicator green',
                pocketlistsItem::PRIORITY_NORM       => '',
            ],
        ];

        return isset($classes[$type][$priority]) ? $classes[$type][$priority] : '';
    }

    /**
     * @return bool
     */
    public static function isPremium()
    {
        return pocketlistsLicensing::isPremium();
    }

    /**
     * @return array
     */
    public static function getPremiumPricing()
    {
        // vofka says sorry for such a hard code
        // we were young and needed the money

        if (wa()->getLocale() == 'ru_RU')
        {
            $pricing = array( 'compare_price' => '', 'price' => '11 999 <span class="ruble">₽</span>', 'special' => '' );
            if (date('Ymd')<='20250531') $pricing = array( 'compare_price' => '11 999', 'price' => '5 999 <span class="ruble">₽</span>', 'special' => '&minus;50% до 31.05', 'special_short' => '&minus;50% до 31.05', 'special_color' => 'green' );
            elseif (date('Ymd')<='20250630') $pricing = array( 'compare_price' => '11 999', 'price' => '7 999 <span class="ruble">₽</span>', 'special' => '&minus;33% до 30.06', 'special_short' => '', 'special_color' => 'orange' );
        }
        else
        {
            $pricing = array( 'compare_price' => '', 'price' => '$199', 'special' => '' );
            if (date('Ymd')<='20250531') $pricing = array( 'compare_price' => '$199', 'price' => '$99', 'special' => '&minus;50% / 05.31', 'special_short' => '&minus;50%', 'special_color' => 'green' );
            elseif (date('Ymd')<='20250630') $pricing = array( 'compare_price' => '$199', 'price' => '$129', 'special' => '&minus;35% / 06.30', 'special_short' => '', 'special_color' => 'orange' );
        }

        return $pricing;
    }
}
