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
        $showTinyAd = $isAdmin
            //&& !wa()->appExists('tasks')
            //&& (date('Y-m') === '2020-11' || date('Y-m') === '2020-12' || date('Y-m') === '2021-01')
            && wa()->getLocale() === 'ru_RU'
            && date('Y-m-d') >= $this->getUser()->getSettings(pocketlistsHelper::APP_ID, 'hide_tiny_ad_until', date('Y-m-d'));

                    $showTinyAd = true;


        if ($showTinyAd) {

            $_tinyAds = array();
            if ( empty(pl2()->getPluginInfo('pro')) ) {
                $_tinyAds[] = array(
                    'adtype' => 'plugin',
                    'appurl' => 'https://www.webasyst.ru/store/app/tasks/?utm_source=pl2webasyst&utm_medium=inapp_fullpage_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa2',
                    'buyurl' => 'https://www.webasyst.ru/buy/store/1811/?utm_source=pl2webasyst&utm_medium=inapp_fullpage_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa2',
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-pro-plugin.png',
                    'title' => 'Pocket Lists PRO',
                    'subtitle' => 'Апгрейд Pocket Lists до максимальных возможностей.',
                    'teaser' => 'Выводит обработку заказов на 80 уровень.',
                    'body' => 'Флагманское приложение Webasyst от 1312 Inc.',
                    'promocode' => wa()->whichUI() == '1.3' ? 'B87K2IZFCZ' : '9UVHYK63V8',
                    'discount' => wa()->whichUI() == '1.3' ? '15' : '20',
                );
            }
            if (1||!wa()->appExists('tasks')) {
                $_tinyAds[] = array(
                    'adtype' => 'app',
                    'appurl' => 'https://www.webasyst.ru/store/app/tasks/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa2',
                    'buyurl' => 'https://www.webasyst.ru/buy/store/1811/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa2',
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-tasks-app-144.png',
                    'title' => 'Задачи',
                    'subtitle' => 'Наводит порядок в работе компании.',
                    'teaser' => 'Флагманское приложение Webasyst от 1312 Inc.',
                    'body' => '<strong>Когда задачи стяновятся слишком большими</strong>, и в формат списков Pocket Lists и быстрых напоминаний не помещаются, поможет наше флагманское приложение для Webasyst — мощный трекер задач (таск-менеджер) для команд от 2 до 100 человек. Реально помогает навести порядок в работе команды. Показывает, кто чем занят. Показывает дедлайны и текущий статус работ.',
                    'promocode' => 'NCGR5G9ZUE',
                    'discount' => '15',
                );
            }
            if (1||!wa()->appExists('cash')) {
                $_tinyAds[] = array(
                    'adtype' => 'app',
                    'appurl' => 'https://www.webasyst.ru/store/app/cash/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_2021',
                    'buyurl' => 'https://www.webasyst.ru/buy/store/1811/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa2',
                    'image' => wa()->getAppStaticUrl() . 'img/pl2ad-cash-app-144.png',
                    'title' => 'Cash Flow',
                    'subtitle' => 'Считает деньги, прогнозирует прибыль.',
                    'teaser' => 'Специальная скидка для пользователей Pocket Lists.',
                    'body' => 'Накладывает повторяющиеся расходы и доходы и показывает баланс денег в кассе наперед',
                    'promocode' => 'Z0J7OV1AHH',
                    'discount' => '15',
                );
            }

            // $tinyAd = $_tinyAds[ rand( 0, count($_tinyAds)-1 ) ];
            $tinyAd = $_tinyAds[ date('z') % count($_tinyAds) ];
        }
        else {
            $tinyAd = null;
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
