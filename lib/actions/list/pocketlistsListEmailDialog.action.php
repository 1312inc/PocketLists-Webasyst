<?php

/**
 * Class pocketlistsListEmailDialogAction
 */
class pocketlistsListEmailDialogAction extends pocketlistsViewAction
{
    /**
     * @throws waException
     */
    public function execute()
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
                    'list'  => new pocketlistsListOutputDecorator($list),
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
                ->getProperSortUndone()
                ->getItemsUndone();

            $this->view->assign(
                [
                    'date'     => $date,
                    'items'    => pocketlistsItemOutputDecorator::decorate($items),
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
                    'items' => pocketlistsItemOutputDecorator::decorate($items),
                ]
            );
        } elseif ($teammate) {
            $user_model = new waUserModel();
            $id = $user_model->getByLogin($teammate);
            if ($id) {
                $contact = new pocketlistsContact(new waContact($id));

                $items = $itemFactory->findAssignedOrCompletesByContact($contact);

                $items = $itemsFilter
                    ->setItems($items)
                    ->filterDoneUndone()
                    ->getProperSortUndone()
                    ->getItemsUndone();

                $this->view->assign(
                    [
                        'teammate' => $teammate,
                        'items'    => pocketlistsItemOutputDecorator::decorate($items),
                    ]
                );
            }
        }
    }
}
