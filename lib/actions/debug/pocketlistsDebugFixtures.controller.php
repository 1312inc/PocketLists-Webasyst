<?php

/**
 * Class pocketlistsDebugFixturesController
 */
class pocketlistsDebugFixturesController extends waJsonController
{
    private $tick = 0;

    /**
     * @throws waException
     */
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new waException('Access denied.', 403);
        }

        $fixturesSettings = waRequest::post('fixtures');
        $listsCount = (int)$fixturesSettings['lists'];
        $itemsCount = (int)$fixturesSettings['items'];

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        /** @var pocketlistsItemLinkFactory $itemLinkFactory */
        $itemLinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);
        /** @var pocketlistsCommentFactory $commentFactory */
        $commentFactory = pl2()->getEntityFactory(pocketlistsComment::class);

        wa('shop');
        $ssOrders = (new shopOrderModel())->getAll('id');
        if ($ssOrders) {
            $ssOrders = array_keys($ssOrders);
        }

        $accessedUsers = pocketlistsRBAC::getAccessContacts();

        $completeProbablity = (int)$fixturesSettings['items_completed'];
        $assignProbablity = (int)$fixturesSettings['items_assigned'];
        $linkSsProbablity = (int)$fixturesSettings['items_linked_ss'];
        $itemWithCommentsProbablity = (int)$fixturesSettings['items_with_comments'];

        /** @var pocketlistsPocket $pocket */
        $pocket = $pocketFactory->createNew();

        $pocket
            ->setName('Focket '.date('m-d:s'))
            ->setColor($this->getRandomFromArray(pocketlistsStoreColor::getColors()));

        $pocketFactory->save($pocket);

        $i = 0;
        while ($i++ < $listsCount) {
            /** @var pocketlistsList $list */
            $list = $listFactory->createNew();

            $list
                ->setName('Fake list '.$i)
                ->setPocket($pocket)
                ->setContactId(wa()->getUser()->getId())
                ->setCreateDatetime(date('Y-m-d H:i:s'))
                ->setColor($pocket->getColor());

            $listFactory->save($list);

            $j = 0;
            $listItemsCount = rand($itemsCount - rand(0, 30), $itemsCount);
            while ($j++ < $listItemsCount) {
                $this->tick++;

                $dueDate = new DateTime(date('Y-m-d H:i:s'));
                $dueDate->modify(sprintf('%s%s days', $this->getRandomValue('-', '+', 5), rand(1, 40)));
                $genDueDate = $this->getRandomValue(1, 0, 15);

                /** @var pocketlistsItem $item */
                $item = $itemFactory->createNew();
                $item
                    ->setName(sprintf('Fake item %s-%s', $i, $j))
                    ->setContactId(wa()->getUser()->getId())
                    ->setList($list)
                    ->setPriority(
                        $this->getRandomValue(
                            $this->getRandomFromArray(pocketlistsItemModel::getPriorities())
                        )
                    )
                    ->setDueDate($genDueDate ? $dueDate->format('Y-m-d') : null)
                    ->setDueDatetime($genDueDate ? $dueDate->format('Y-m-d H:i:s') : null)
                    ->setCreateDatetime(date('Y-m-d H:i:s'));

                if ($this->getProbability($assignProbablity) && $accessedUsers) {
                    $item->setAssignedContactId($this->getRandomFromArray($accessedUsers));
                }

                if ($this->getProbability($completeProbablity)) {
                    $item
                        ->setStatus(pocketlistsItem::STATUS_DONE)
                        ->setCompleteContactId($item->getAssignedContactId() ? $item->getAssignedContactId() : $item->getContactId())
                        ->setCompleteDatetime(date('Y-m-d H:i:s'));
                }

                $itemFactory->save($item);

                if ($this->getProbability($linkSsProbablity) && $ssOrders) {
                    $itemLinkFactory->createFromDataForItem(
                        $item,
                        [
                            'app'         => 'shop',
                            'entity_type' => 'order',
                            'entity_id'   => $this->getRandomFromArray($ssOrders),
                        ]
                    );
                }

                if ($this->getProbability($itemWithCommentsProbablity)) {
                    $itemCommentsCount = mt_rand(0, 10);

                    while($itemCommentsCount--) {
                        /** @var pocketlistsComment $comment */
                        $comment = $commentFactory->createNew();
                        $comment
                            ->setItem($item)
                            ->setContactId($item->getContactId())
                            ->setComment(sprintf('Случайный комментарий %s', mt_rand(1000, PHP_INT_MAX)))
                            ->setCreateDatetime(date('Y-m-d H:i:s'));

                        $commentFactory->insert($comment);
                    }
                }
            }
        }

        $this->response = 'ok';
    }

    /**
     * @param array $array
     *
     * @return mixed
     */
    private function getRandomFromArray($array)
    {
        return $array[array_rand($array)];
    }

    /**
     * @param int $value
     * @param int $default
     * @param int $randomness
     *
     * @return mixed
     */
    private function getRandomValue($value = 1, $default = 0, $randomness = 9)
    {
        return $this->tick % $randomness === 0 ? $value : $default;
    }

    /**
     * @param int $prob
     *
     * @return bool
     */
    private function getProbability($prob)
    {
        return mt_rand(0, 100) <= $prob;
    }
}
