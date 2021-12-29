<?php

/**
 * Проверяет можно ли показывать рекламу и вернет её если да
 */
final class pocketlistsTinyAddService
{
    private const MIN_ITEMS_COUNT = 50;

    /**
     * @param waContact $user
     *
     * @return array
     * @throws waException
     */
    public function getAds(waContact $user): array
    {
        $showTinyAd = $this->canShowWithItemsCount($user)
            && date('Y-m-d') >= $user->getSettings(
                pocketlistsHelper::APP_ID,
                'hide_tiny_ad_until',
                date('Y-m-d')
            );

        $tinyAd = [];
        $_tinyAds = [];

        if ($showTinyAd) {
            $_webasyst_base_url = (wa()->getLocale() === 'ru_RU')
                ? 'https://www.webasyst.ru/'
                : 'https://www.webasyst.com/';
            $_whichUI = (wa()->whichUI() == '1.3') ? '1' : '2'; //utm

            if (empty(pl2()->getPluginInfo('pro')) && wa()->getLocale() === 'ru_RU') {
                $_tinyAds[] = [
                    'adtype' => 'plugin',
                    'heading' => _w('Promocode'),
                    'appurl' => $_webasyst_base_url . 'store/plugin/pocketlists/pro/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa' . $_whichUI,
                    'buyurl' => $_webasyst_base_url . 'buy/store/5045/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa' . $_whichUI,
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-pro-plugin.png',
                    'title' => 'Pocket Lists PRO',
                    'subtitle' => 'Поможет поднять обработку заказов на 80 уровень.',
                    'teaser' => 'Промокод на автоматизацию Shop-Script &rarr; Pocket Lists.',
                    'body' => '<strong>Главная фишка плагина — автоматическое создание задач менеджерам при действиях с заказами.</strong> В обработку, отправлен, возврат — при каждом подобном действии с заказами нужным сотрудникам будут автоматически ставиться задачи согласно вашим настройкам. Не пропустите ни одной продажи!',
                    'promocode' => wa()->whichUI() == '1.3' ? 'B87K2IZFCZ' : '9UVHYK63V8',
                    'discount' => '20',
                ];
            }
            if (!wa()->appExists('tasks')) {
                $_tinyAds[] = [
                    'adtype' => 'app',
                    'heading' => _w('More apps by 1312 Inc.'),
                    'appurl' => $_webasyst_base_url . 'store/app/tasks/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa' . $_whichUI,
                    'buyurl' => $_webasyst_base_url . 'buy/store/1811/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa' . $_whichUI,
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-tasks-app-144.png',
                    'title' => _w('Teamwork'),
                    'subtitle' => _w('When tasks become bigger and more complex.'),
                    'teaser' => _w('Promocode for our flagship Webasyst app.'),
                    'body' => '<strong>' . _w('Our flagship app.') . '</strong>' . ' ' .
                        _w(
                            'Amazing companion/upgrade for Pocket Lists when it’s time for real collaboration on <em>bigger and more complex tasks</em>. Assignments, task statuses, deadlines, kanban board, more — the app help bringing the order to any complex teamwork.'
                        ),
                    'promocode' => 'X97JNTWP8I',
                    'discount' => '15',
                ];
            }
            if (!wa()->appExists('cash')) {
                $_tinyAds[] = [
                    'adtype' => 'app',
                    'heading' => _w('More apps by 1312 Inc.'),
                    'appurl' => $_webasyst_base_url . 'store/app/cash/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_wa' . $_whichUI,
                    'buyurl' => $_webasyst_base_url . 'buy/store/5136/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_wa' . $_whichUI,
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-cash-app-144.png',
                    'title' => _w('Cash Flow'),
                    'subtitle' => _w('Forecasts and saves your business money.'),
                    'teaser' => _w('Promocode for managing money the smarter way.'),
                    'body' => '<strong>' . _w('Forecasts and saves your money.') . '</strong>' . ' ' .
                        _w(
                            'Shows exact cash on hand balance for any date in the future. This app could have been a <em>life saver</em> for most businesses which did not survive a cash gap because of not knowing it’s coming.'
                        ),
                    'promocode' => 'Z3NTSO2IHE',
                    'discount' => '15',
                ];
            }

            if (count($_tinyAds) > 0) {
                $tinyAd = $_tinyAds[date('z') % count($_tinyAds)];
            } //show random tiny ad based on a day
        }

        return $tinyAd;
    }

    private function canShowWithItemsCount(waContact $user): bool
    {
        $itemsCount = $user->getSettings(
            pocketlistsHelper::APP_ID,
            'tiny_ad_items_count',
            0
        );

        if ($itemsCount < self::MIN_ITEMS_COUNT) {
            $user->setSettings(
                pocketlistsHelper::APP_ID,
                'tiny_ad_items_count',
                pl2()->getModel(pocketlistsItem::class)->countAll()
            );

            return false;
        }

        return true;
    }
}
