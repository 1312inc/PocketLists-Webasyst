<?php

/**
 * Class pocketlistsBackendSidebarAction
 */
class pocketlistsBackendSidebarAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $sidebar_todo_count_icon = pl2()->getEntityCounter()->countTodoUndoneWithUserPrioritiesItems();
        $sidebar_todo_count = pl2()->getEntityCounter()->countTodoUndoneItems()->getCount();

        $this->view->assign(compact('sidebar_todo_count', 'sidebar_todo_count_icon'));

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
        $lists = $listFactory->findAllActive();
        $this->view->assign('lists', $lists);

        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contactFactory->getTeammates(pocketlistsRBAC::getAccessContacts(), true, true, true);

        foreach ($teammates as $tid => $teammate) {
            if (!$teammate->isExists()) {
                unset($teammates[$tid]);
            }
        }
        $this->view->assign('team', $teammates);

        $last_activity = pocketlistsActivity::getUserActivity();

        /** @var pocketlistsCommentModel $commentModel */
        $commentModel = pl2()->getModel(pocketlistsComment::class);

        $favoritesCount = pl2()->getEntityCounter()->countFavoritesItems(
            $this->user,
            false,
            false,
            pocketlistsItem::STATUS_UNDONE
        );
        $this->view->assign(
            [
                'new_comments_count'     => $commentModel->getLastActivityComments($last_activity),
                'new_items_count'        => $itemModel->getLastActivityItems($last_activity),
                'last_activity'          => $last_activity,
                'favorites_count_undone' => $favoritesCount,
            ]
        );

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        $pockets = $pocketFactory->findAllForUser();

        $linkedApps = pl2()->getLinkedApp();
        foreach ($linkedApps as $i => $app) {
            if (!$app->userCanAccess(null, 'sidebar')) {
                unset($linkedApps[$i]);
            }
        }

        /**
         * UI in main sidebar
         * @event backend_sidebar
         *
         * @param pocketlistsEventInterface $event Event object
         * @return string HTML output
         */
        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_BACKEND_SIDEBAR);
        $eventResult = pl2()->waDispatchEvent($event);

        $isAdmin = $this->getUser()->isAdmin('pocketlists');
        $showTinyAd = 1 //$isAdmin
            //&& !wa()->appExists('tasks')
            //&& (date('Y-m') === '2020-11' || date('Y-m') === '2020-12' || date('Y-m') === '2021-01')
            //&& wa()->getLocale() === 'ru_RU'
            && date('Y-m-d') >= $this->getUser()->getSettings(pocketlistsHelper::APP_ID, 'hide_tiny_ad_until', date('Y-m-d'));

        $tinyAd = null;

        if ($showTinyAd) {

            $_webasyst_base_url = ( wa()->getLocale() === 'ru_RU' ) ? 'https://www.webasyst.ru/' : 'https://www.webasyst.com/';
            $_whichUI = ( wa()->whichUI() == '1.3' ) ? '1' : '2'; //utm

            $_tinyAds = array();
            if ( empty(pl2()->getPluginInfo('pro')) && wa()->getLocale() === 'ru_RU' ) {
                $_tinyAds[] = array(
                    'adtype' => 'plugin',
                    'heading' => _w('Promocode'),
                    'appurl' => $_webasyst_base_url . 'store/plugin/pocketlists/pro/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa'.$_whichUI,
                    'buyurl' => $_webasyst_base_url . 'buy/store/5045/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa'.$_whichUI,
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-pro-plugin.png',
                    'title' => 'Pocket Lists PRO',
                    'subtitle' => 'Поможет поднять обработку заказов на 80 уровень.',
                    'teaser' => 'Промокод на автоматизацию Shop-Script &rarr; Pocket Lists.',
                    'body' => '<strong>Главная фишка плагина — автоматическое создание задач менеджерам при действиях с заказами.</strong> В обработку, отправлен, возврат — при каждом подобном действии с заказами нужным сотрудникам будут автоматически ставиться задачи согласно вашим настройкам. Не пропустите ни одной продажи!',
                    'promocode' => wa()->whichUI() == '1.3' ? 'B87K2IZFCZ' : '9UVHYK63V8',
                    'discount' => '20',
                );
            }
            if (!wa()->appExists('tasks')) {
                $_tinyAds[] = array(
                    'adtype' => 'app',
                    'heading' => _w('More apps by 1312 Inc.'),
                    'appurl' => $_webasyst_base_url . 'store/app/tasks/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa'.$_whichUI,
                    'buyurl' => $_webasyst_base_url . 'buy/store/1811/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa'.$_whichUI,
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-tasks-app-144.png',
                    'title' => _w('Teamwork'),
                    'subtitle' => _w('When tasks become bigger and more complex.'),
                    'teaser' => _w('Promocode for our flagship Webasyst app.'),
                    'body' => '<strong>' . _w('Our flagship app.') . '</strong>' . ' ' .
                    _w('Amazing companion/upgrade for Pocket Lists when it’s time for real collaboration on <em>bigger and more complex tasks</em>. Assignments, task statuses, deadlines, kanban board, more — the app help bringing the order to any complex teamwork.'),
                    'promocode' => 'NCGR5G9ZUE',
                    'discount' => '15',
                );
            }
            if (!wa()->appExists('cash')) {
                $_tinyAds[] = array(
                    'adtype' => 'app',
                    'heading' => _w('More apps by 1312 Inc.'),
                    'appurl' => $_webasyst_base_url . 'store/app/cash/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_wa'.$_whichUI,
                    'buyurl' => $_webasyst_base_url . 'buy/store/5136/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_wa'.$_whichUI,
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-cash-app-144.png',
                    'title' => _w('Cash Flow'),
                    'subtitle' => _w('Forecasts and saves your business money.'),
                    'teaser' => _w('Promocode for managing money the smarter way.'),
                    'body' => '<strong>' . _w('Forecasts and saves your money.') . '</strong>' . ' ' .
                    _w('Shows exact cash on hand balance for any date in the future. This app could have been a <em>life saver</em> for most businesses which did not survive a cash gap because of not knowing it’s coming.'),
                    'promocode' => 'Z0J7OV1AHH',
                    'discount' => '15',
                );
            }

            if ( count($_tinyAds) > 0 )
                $tinyAd = $_tinyAds[ date('z') % count($_tinyAds) ]; //show random tiny ad based on a day
        }

        $this->view->assign(compact('pockets', 'linkedApps'));
        $this->view->assign(
            [
                'backend_sidebar' => $eventResult,
                'isAdmin' => $isAdmin,
                'showTinyAd' => $showTinyAd,
                'tinyAd' => $tinyAd,
            ]
        );
    }
}
