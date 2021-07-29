<?php

/**
 * Class pocketlistsTeamAction
 */
class pocketlistsTeamAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsForbiddenException
     * @throws waException
     */
    public function runAction($params = null)
    {
        if (!pocketlistsRBAC::canAssign()) {
            throw new pocketlistsForbiddenException();
        }

        // get all pocketlists users
        // all admin
        $teammates = [];
        $teammates_ids = pocketlistsRBAC::getAccessContacts();
        $teammate = null;

        if ($teammates_ids) {
            /** @var pocketlistsContactFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsContact::class);
            $teammates = $factory->getTeammates($teammates_ids);

            /** @var pocketlistsContact $teammate */
            $selected_teammate = waRequest::get('teammate');
            $lists = [];
            if ($selected_teammate) {
                $user_model = new waUserModel();
                $id = $user_model->getByLogin($selected_teammate);
                $teammate = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($id['id']);

                /** @var pocketlistsListFactory $listFactory */
                $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
                $lists = $listFactory->findForTeammate($teammate);

                $listFilter = new pocketlistsStrategyListFilterAndSort($lists);
                $lists = $listFilter->filter()->getNonArchived();

                /** @var pocketlistsList $list */
                foreach ($lists as $list_id => $list) {
                    $activity = $teammate->getListActivities($list);
                    if (!empty($activity['last_date'])) {
                        $list->setLastContactAtivity($activity['last_date']);
                    }
                }

                $lists = $listFilter->sortUnarchivedByActivity();
            } else {
                $teammate = reset($teammates);
            }

            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

            $itemsUndone = $itemFactory->findAssignedOrCompletesUndoneByContact($teammate);
            $itemsDone = $itemFactory
                ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
                ->setOffset(pocketlistsFactory::DEFAULT_OFFSET)
                ->findAssignedOrCompletesDoneByContact($teammate);

            $countDoneItems = pl2()->getModel(pocketlistsItem::class)
                ->countAssignedOrCompletesDoneByContactItems($teammate->getId());

            $this->view->assign(
                [
                    'lists' => $lists,
                    'items_done' => $itemsDone,
                    'items' => $itemsUndone,
                    'count_done_items' => $countDoneItems,
                    'current_teammate' => $teammate,
                ]
            );
        }

        $external = waRequest::request('external', 0, waRequest::TYPE_INT);
        $externalApp = waRequest::request('external_app', null, waRequest::TYPE_STRING_TRIM);

        /**
         * UI hook in teammate right sidebar
         *
         * @event backend_teammate_sidebar
         *
         * @param pocketlistsEventInterface $event Event with pocketlistsContact object and external flag in params array
         *
         * @return string html output
         */
        $event = new pocketlistsEvent(
            pocketlistsEventStorage::WA_BACKEND_TEAMMATE_SIDEBAR,
            $teammate,
            ['external' => $external, 'external_app' => $externalApp]
        );
        $eventResult = pl2()->waDispatchEvent($event);

        $this->view->assign(
            [
                'teammates' => $teammates,
                'print' => waRequest::get('print', false),
                'user' => $this->user,
                'external' => $external,
                'externalApp' => $externalApp,
                'itemAdd' => (new pocketlistsItemAddAction(
                    [
                        'teammate' => $teammate,
                        'external' => $external,
                        'externalApp' => $externalApp,
                    ]
                ))->display(false),
                'backend_teammate_sidebar' => $eventResult,
            ]
        );

        $this->setTemplate(pl2()->getUI2TemplatePath('templates/actions%s/team/Team.html', $externalApp));
    }
}
