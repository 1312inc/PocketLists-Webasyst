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
     * @return bool
     */
    public static function isCloud()
    {
        return wa()->appExists('hosting');
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
            $pricing = array( 'compare_price' => '15 999', 'price' => '5 999 <span class="ruble">₽</span> / год', 'special' => '', 'special_color' => 'green' );
            if (date('Ymd')<='20250831') $pricing = array( 'compare_price' => '15 999', 'price' => '11 999 <span class="ruble">₽</span> / навсегда', 'special' => '&minus;25% до 31.08', 'special_short' => '&minus;25%', 'special_color' => 'green' );
        }
        else
        {
            $pricing = array( 'compare_price' => '$269', 'price' => '$99 / year', 'special' => '', 'special_color' => 'green' );
            if (date('Ymd')<='20250831') $pricing = array( 'compare_price' => '$269', 'price' => '$199  / lifetime', 'special' => '&minus;25% / 08.31', 'special_short' => '&minus;25%', 'special_color' => 'green' );
        }

        return $pricing;
    }
}
