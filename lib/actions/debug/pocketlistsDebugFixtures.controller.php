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

        $pocket = new pocketlistsPocketModel(
            [
                'name'  => 'Focket '.date('m-d:s'),
                'color' => $this->getRandomFromArray(pocketlistsStoreColor::getColors()),
            ]
        );
        $pocket->save();

        $i = 0;
        while ($i++ < $listsCount) {
            $list = new pocketlistsListModel(
                [
                    'name'            => 'Fake list '.$i,
                    'pocket_id'       => $pocket->getPk(),
                    'type'            => pocketlistsListModel::TYPE_CHECKLIST,
                    'archived'        => 0,
                    'contact_id'      => wa()->getUser()->getId(),
                    'create_datetime' => date('Y-m-d H:i:s'),
                    'color'           => $pocket->color,
                ]
            );
            $list->save();

            $j = 0;
            $listItemsCount = rand($itemsCount - rand(0, 30), $itemsCount);
            while ($j++ < $listItemsCount) {
                $this->tick++;

                $dueDate = new DateTime(date('Y-m-d H:i:s'));
                $dueDate->modify(sprintf('%s%s days', $this->getRandomValue('-', '+', 5), rand(1, 40)));
                $genDueDate = $this->getRandomValue(1, 0, 15);

                $item = new pocketlistsItemModel(
                    [
                        'name'            => sprintf('Fake item %s-%s', $i, $j),
                        'status'          => 0,
                        'contact_id'      => wa()->getUser()->getId(),
                        'list_id'         => $list->getPk(),
                        'priority'        => $this->getRandomValue(
                            $this->getRandomFromArray(pocketlistsItemModel::getPriorities())
                        ),
                        'due_date'        => $genDueDate ? $dueDate->format('Y-m-d') : null,
                        'due_datetime'    => $genDueDate ? $dueDate->format('Y-m-d H:i:s') : null,
                        'create_datetime' => date('Y-m-d H:i:s'),
                    ]
                );
                $item->save();
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
}
