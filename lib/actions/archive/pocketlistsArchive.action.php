<?php

/**
 * Class pocketlistsArchiveAction
 */
class pocketlistsArchiveAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl(null, true));
            }
        }

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        /** @var pocketlistsList[] $lists */
        $lists = $listFactory->findLists();
        $lists = (new pocketlistsStrategyListFilterAndSort($lists))->filter()->getArchived();

        $list_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $list_id = ($list_id ?: waRequest::get('id', 0, waRequest::TYPE_INT));
        $list = null;

        if ($list_id) {
            /** @var pocketlistsList $list */
            $list = $listFactory->findById($list_id);
            if (!$list || ($list && !$list->isArchived())) {
                $this->redirect(wa()->getAppUrl(null, true).'archive');
            }
        } elseif ($lists) {
            $list = reset($lists);
        }

        if (wa()->whichUI() !== '1.3') {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        $this->setTemplate(wa()->getAppPath(sprintf('templates/actions%s/archive/Archive.html', pl2()->getUI2TemplatePath())));

        if ($list) {
            if (!pocketlistsRBAC::canAccessToList($list)) {
                $this->view->assign(
                    'error',
                    [
                        'code'    => 403,
                        'message' => _w('Access denied'),
                    ]
                );
                $this->setTemplate(pl2()->getUI2TemplatePath('templates/include%s/error.html'));

                return;
            }

            /** @var pocketlistsContactFactory $factory */
            $factory = pl2()->getEntityFactory(pocketlistsContact::class);
            $list_access_contacts = $factory->getTeammates(
                pocketlistsRBAC::getAccessContacts($list),
                true,
                false
            );

            /** @var pocketlistsItemModel $itemModel */
            $itemModel = pl2()->getModel(pocketlistsItem::class);

            $count_undone = $itemModel->countByField(
                [
                    'list_id' => $list_id,
                    'status'  => 0,
                ]
            );
            $count_done = $itemModel->countByField(
                [
                    'list_id' => $list_id,
                    'status'  => 1,
                ]
            );

            $undone = $list->getUndoneItems();

            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
            $done = $itemFactory
                ->setOffset(0)
                ->setLimit(pocketlistsItemFactory::DEFAULT_LIMIT)
                ->findDoneByList($list);

            $this->view->assign([
                'items'                => $undone,
                'empty'                => count($undone),
                'items_done'           => $done,
                'count_items_done'     => $count_done,
                'count_items_undone'   => $count_undone,
                'new'                  => false,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'list_access_contacts' => $list_access_contacts,
                'list'                 => $list,
                'lists'                => $lists,
                'user'                 => $this->user,
            ]);
        }
    }
}
