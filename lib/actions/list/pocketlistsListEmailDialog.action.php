<?php

/**
 * Class pocketlistsListEmailDialogAction
 */
class pocketlistsListEmailDialogAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $id = waRequest::get('id', false, waRequest::TYPE_INT);
        $date = waRequest::get('date', '');
        $favorite = waRequest::get('favorite', '');
        $teammate = waRequest::get('teammate', '');

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        $email = wa()->getUser()->get('email', 'default');
        $this->view->assign('email', $email);

        $itemsFilter = new pocketlistsStrategyItemFilterAndSort();

        if ($id) {
            /** @var pocketlistsList $list */
            $list = $listFactory->findById($id);

            $this->view->assign(
                [
                    'list'  => $list,
                    'items' => $list->getUndoneItems(),
                ]
            );
        } elseif ($favorite) {
            if ($date === 'today') {
                $date = false;
            }

            $items = $itemFactory->findFavoritesForUser($this->user);

            $items = $itemsFilter
                ->setItems($items)
                ->filterDoneUndone()
                ->getProperSortUndone();

            $this->view->assign(
                [
                    'date'     => $date,
                    'items'    => $items,
                    'favorite' => true,
                ]
            );
        } elseif ($date) {
            if ($date === 'today') {
                $date = false;
            }

            $items = $itemFactory->findToDo($this->user, $date);

            $items = $itemsFilter
                ->setItems($items)
                ->filterDoneUndone()
                ->getItemsUndone();

            $this->view->assign(
                [
                    'date'  => $date,
                    'items' => $items,
                ]
            );
        } elseif ($teammate) {
            $user_model = new waUserModel();
            $id = $user_model->getByLogin($teammate);
            if (isset($id['id'])) {
                $contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($id['id']);

                $items = $itemFactory->findAssignedOrCompletesByContact($contact);

                $items = $itemsFilter
                    ->setItems($items)
                    ->filterDoneUndone()
                    ->getProperSortUndone();

                $this->view->assign(
                    [
                        'teammate' => $teammate,
                        'items'    => $items,
                    ]
                );
            }
        }
    }
}
