<?php

/**
 * Class pocketlistsPocketAction
 */
class pocketlistsPocketAction extends pocketlistsViewPocketAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsNotFoundException
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $id = waRequest::get('id', 0, waRequest::TYPE_INT);
        $list_id = waRequest::get('list_id', false, waRequest::TYPE_INT);

        $available_pockets = pocketlistsRBAC::getAccessPocketForContact();
//        if ($id && !in_array($id, $available_pockets)) {
//            throw new waException('Access denied.', 403);
//        }

        $last_pocket_list_id = $this->user->getSettings()->getLastPocketList();

        if (!$id) {
            if (isset($last_pocket_list_id['pocket_id'])) { // last visited pocket
                $id = $last_pocket_list_id['pocket_id'];
            } else { // first of available pockets
                $id = reset($available_pockets);
            }
        }

        // check if user have access to this pocket/list
        if (!in_array($id, $available_pockets) ||
            (isset($last_pocket_list_id['pocket_id']) &&
                !in_array($last_pocket_list_id['pocket_id'], $available_pockets))
        ) {
            $id = reset($available_pockets);
        }

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);

        if (!$id) {
            $allPockets = $pocketFactory->findAllForUser();
            $pocket = reset($allPockets);
        } else {
            /** @var pocketlistsPocket $pocket */
            $pocket = $this->getPocket($id);
        }

        $lists = $pocket->getUserLists();
        $lists = (new pocketlistsStrategyListFilterAndSort($lists))->filter()->getNonArchived();

        if (!$list_id) {
            if ($list_id < 0 && isset($last_pocket_list_id['list_id']) && $last_pocket_list_id['pocket_id'] == $pocket['id']) {
                $list_id = $last_pocket_list_id['list_id'];
            } else {
                if ($lists) {
                    $firtsList = reset($lists);
                    $list_id = $firtsList->getId();
                    $last_pocket_list_id = ['pocket_id' => $id, 'list_id' => $list_id];
                } else {
                    $last_pocket_list_id = ['pocket_id' => $id];
                }
            }
        } else {
            $last_pocket_list_id = ['pocket_id' => $id, 'list_id' => $list_id];
        }

        if ($list_id != -1) {
            $this->user->getSettings()->set('last_pocket_list_id', json_encode($last_pocket_list_id));
        }

        $lists_html = (new pocketlistsListAction(['list_id' => $list_id, 'pocket_id' => $pocket->getId()]))->display(false);

        /**
         * UI hook in pocket sidebar
         * @event backend_pocket
         *
         * @param pocketlistsEventInterface $event Event with pocketlistsPocket object and it lists in params array
         * @return string html output
         */
        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_BACKEND_POCKET, $pocket, ['lists' => $lists]);
        $eventResult = pl2()->waDispatchEvent($event);

        $this->view->assign(
            [
                'lists_html' => $lists_html,
                'isAdmin'    =>
                    pocketlistsRBAC::contactHasAccessToPocket($pocket) == pocketlistsRBAC::RIGHT_ADMIN ? 1 : 0
                ,
                'lists'      => $lists,
                'list_id'    => $list_id,
                'pocket'     => $pocket,

                'backend_pocket' => $eventResult,
            ]
        );
    }
}
