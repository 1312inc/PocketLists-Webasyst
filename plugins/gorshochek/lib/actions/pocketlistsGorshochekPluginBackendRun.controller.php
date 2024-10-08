<?php

class pocketlistsGorshochekPluginBackendRunController extends waLongActionController
{
    const DEFAULT_COUNT = 100;

    /**
     * @return void
     */
    protected function init()
    {
        $entity_count = waRequest::post('entity_count', 0, waRequest::TYPE_INT);
        $entity_type = waRequest::post('entity', 'ITEM', waRequest::TYPE_STRING_TRIM);
        $by_user = waRequest::post('by_user', 0, waRequest::TYPE_INT);
        $assign = waRequest::post('assign', 0, waRequest::TYPE_INT);
        $list = waRequest::post('list', '', waRequest::TYPE_STRING_TRIM);
        $comment_in = waRequest::post('comment_in', '', waRequest::TYPE_STRING_TRIM);
        $comment_entity_id = waRequest::post('comment_entity_id', 0, waRequest::TYPE_INT);
        $is_log = !!waRequest::post('is_log', 0, waRequest::TYPE_INT);

        $this->data = [
            'counter'           => 0,
            'count_all'         => $entity_count,
            'entity_type'       => strtolower($entity_type),
            'by_user'           => (empty($by_user) ? pocketlistsRBAC::getAccessContacts() : [$by_user]),
            'assign'            => $assign,
            'list'              => $list,
            'comment_in'        => $comment_in,
            'comment_entity_id' => $comment_entity_id,
            'is_log'            => $is_log,
            'send_log'          => []
        ];
    }

    /**
     * @return bool
     * @throws ReflectionException
     * @throws waException
     */
    protected function step()
    {
        switch ($this->data['entity_type']) {
            case 'pocket':
                $count = $this->addPocket();
                break;
            case 'list':
                $count = $this->addList();
                break;
            case 'comment':
                $count = $this->addComment();
                break;
            case 'item':
            default:
                $count = $this->addItem();
        }
        $this->data['counter'] += $count;

        return true;
    }

    /**
     * @return bool
     */
    protected function isDone()
    {
        $is_done = $this->data['counter'] >= $this->data['count_all'];

        if ($this->data['is_log'] && !empty($this->data['send_log'])) {
            if ($is_done || count($this->data['send_log']) >= self::DEFAULT_COUNT) {
                try {
                    pocketlistsLogService::multipleAdd(
                        $this->data['entity_type'],
                        pocketlistsLog::ACTION_ADD,
                        $this->data['send_log']
                    );
                } catch (Exception $e) {}
                $this->data['send_log'] = [];
            }
        } else {
            $this->data['send_log'] = [];
        }

        return $is_done;
    }

    /**
     * @param $filename
     * @return bool
     * @throws waException
     */
    protected function finish($filename)
    {
        $this->info();
        if ($this->getRequest()::post('cleanup')) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     * @throws waException
     */
    protected function info()
    {
        $progress = number_format($this->data['counter'] * 100 / $this->data['count_all']);
        $html  = sprintf('Сварено: %s из %s', $this->data['counter'], $this->data['count_all']);
        $this->response([
            'processid' => $this->processId,
            'ready'     => $this->isDone(),
            'text'      => $html
        ]);
    }

    /**
     * @param $response
     * @return void
     */
    private function response($response = [])
    {
        $this->getResponse()->addHeader('Content-Type', 'application/json');
        $this->getResponse()->sendHeaders();
        echo waUtils::jsonEncode($response);
    }

    /**
     * @return int
     * @throws ReflectionException
     * @throws waException
     */
    private function addItem()
    {
        $result = 0;
        /** @var pocketlistsItemFactory $item_factory */
        $item_factory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        $item = $item_factory->createNew();

        while (empty($result)) {
            $gen_data = $this->generatorData('item');
            $item = pl2()->getHydrator()->hydrate($item, $gen_data);
            if ($item_factory->insert($item)) {
                $this->data['send_log'][] = $gen_data + ['item_id' => $item->getId()];
                $result++;
            }
        }

        return $result;
    }

    /**
     * @return int
     * @throws ReflectionException
     * @throws waException
     */
    private function addList()
    {
        $result = 0;
        /** @var pocketlistsListFactory $list_factory */
        $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

        /** @var pocketlistsList $list */
        $list = $list_factory->createNew();

        while (empty($result)) {
            $gen_data = $this->generatorData('list');
            $list = pl2()->getHydrator()->hydrate($list, $gen_data);
            if ($list_factory->save($list)) {
                $this->data['send_log'][] = $gen_data + ['list_id' => $list->getId()];
                $result++;
            }
        }

        return $result;
    }

    /**
     * @return int
     * @throws ReflectionException
     * @throws waException
     */
    private function addPocket()
    {
        $result = 0;
        /** @var pocketlistsPocketFactory $pocket_factory */
        $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = $pocket_factory->createNew();

        while (empty($result)) {
            $gen_data = $this->generatorData('pocket');
            $pocket = pl2()->getHydrator()->hydrate($pocket, $gen_data);
            if ($pocket_factory->save($pocket)) {
                $this->data['send_log'][] = $gen_data + ['pocket_id' => $pocket->getId()];
                $result++;
            }
        }

        return $result;
    }

    private function addComment()
    {
        $result = 0;
        /** @var pocketlistsCommentFactory $comment_factory */
        $comment_factory = pl2()->getEntityFactory(pocketlistsComment::class);

        /** @var pocketlistsComment $comment */
        $comment = $comment_factory->createNew();

        while (empty($result)) {
            $gen_data = $this->generatorData('comment');
            $comment = pl2()->getHydrator()->hydrate($comment, $gen_data);
            if ($comment_factory->save($comment)) {
                $this->data['send_log'][] = $gen_data + ['comment_id' => $comment->getId()];
                $result++;
            }
        }

        return $result;
    }

    /**
     * @param $entity_type
     * @return array
     * @throws waException
     */
    private function generatorData($entity_type) {
        switch ($entity_type) {
            case 'pocket':
                $gen_data = [
                    'name'  => $this->genName($entity_type),
                    'color' => $this->genColor(),
                    'uuid'  => $this->getUuid(2)
                ];
                break;
            case 'list':
                $gen_data = [
                    'pocket_id' => $this->genPocketId(),
                    'name'      => $this->genName($entity_type),
                    'type'      => $this->genType(),
                    'icon'      => $this->genIcon(),
                    'color'     => $this->genColor(),
                    'uuid'      => $this->getUuid(3)
                ];
                break;
            case 'item':
                list($name, $note) = $this->genName($entity_type);
                $gen_data = [
                    'list_id'             => $this->genListId(),
                    'name'                => $name,
                    'note'                => $note,
                    'priority'            => $this->genPriority(),
                    'contact_id'          => $this->genContactId(),
                    'create_datetime'     => date('Y-m-d H:i:s', $this->genTimestamp()),
                    'due_datetime'        => null,
                    'due_date'            => null,
                    'assigned_contact_id' => $this->genContactId(true),
                    'files'               => [],
                    'amount'              => 0,
                    'repeat'              => 0,
                    'uuid'                => $this->getUuid(10)
                ];
                if (mt_rand(1, 4) % 4 === 0) {
                    $is_future = (mt_rand(1, 3) % 3 === 0);
                    $time = $this->genTimestamp($is_future);
                    $gen_data['due_date'] = date('Y-m-d', $time);
                    $gen_data['due_datetime'] = date('Y-m-d H:i:s', $time);
                }
                break;
            case 'comment':
                $gen_data = [
                    'item_id'         => $this->genItemId(),
                    'contact_id'      => $this->genContactId(),
                    'comment'         => $this->genName($entity_type),
                    'create_datetime' => date('Y-m-d H:i:s', $this->genTimestamp()),
                    'uuid'            => $this->getUuid(10)
                ];
                break;
            default:
                $gen_data = [];
        }

        return $gen_data;
    }

    private function genContactId($is_assigned = false)
    {
        if ($is_assigned) {
            return ifset($this->data, 'assign', null);
        }

        $rand = mt_rand(0, count($this->data['by_user']) - 1);

        return ifset($this->data, 'by_user', $rand, null);
    }

    /**
     * @return string
     */
    private function genColor()
    {
        if (empty($this->data['color'])) {
            $this->data['color'] = array_keys(pocketlistsStoreColor::getColors());
        }
        $rand = mt_rand(0, count($this->data['color']) - 1);

        return ifset($this->data, 'color', $rand, pocketlistsStoreColor::NONE);
    }

    /**
     * @return string
     */
    private function genIcon()
    {
        if (empty($this->data['icons'])) {
            $this->data['icons'] = [];
            $list_icons = (new pocketlistsListIcon())->getAll();
            foreach ($list_icons as $_list_icon) {
                $this->data['icons'] = array_merge($this->data['icons'], array_values($_list_icon));
            }
        }
        $rand = mt_rand(0, count($this->data['icons']) - 1);

        return ifset($this->data, 'icons', $rand, pocketlistsList::DEFAULT_ICON);
    }

    /**
     * @return int
     */
    private function genPriority()
    {
        return mt_rand(0, 5);
    }

    /**
     * @return string
     */
    private function genType()
    {
        return (mt_rand(0, 1) ? pocketlistsList::TYPE_CHECKLIST : pocketlistsList::TYPE_NOTES);
    }

    /**
     * -3 года ----------------- сегодня ----------------- +1 год
     * @param $is_future
     * @return int
     */
    private function genTimestamp($is_future = false)
    {
        static $past;
        static $future;
        if (empty($past)) {
            $past = strtotime('-3 year');
        }
        if (empty($future)) {
            $future = strtotime('+1 year');
        }
        if ($is_future) {
            $time = mt_rand(time(), $future);
        } else {
            $time = mt_rand($past, time());
        }

        return $time;
    }

    /**
     * @return string|null
     * @throws waException
     */
    private function genPocketId()
    {
        if (empty($this->data['pocket_ids'])) {
            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $pockets = $pocket_model->getAllPockets();
            $this->data['pocket_ids'] = array_column($pockets, 'id');
            unset($pocket_model, $pockets);
        }
        $rand = mt_rand(0, count($this->data['pocket_ids']) - 1);

        return ifset($this->data, 'pocket_ids', $rand, null);
    }

    /**
     * @return mixed|null
     * @throws waDbException
     * @throws waException
     */
    private function genListId()
    {
        if ($this->data['list'] === 'no') {
            return null;
        }
        if (empty($this->data['list_ids'])) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $all_lists = $list_model->getAllLists();
            $this->data['list_ids'] = array_column($all_lists, 'id');
            unset($list_model, $all_lists);
        }
        $rand = mt_rand(0, count($this->data['list_ids']) - 1);

        return ifset($this->data, 'list_ids', $rand, null);
    }

    private function genItemId()
    {
        if (empty($this->data['item_ids'])) {
            if ($this->data['comment_in'] === 'item') {
                $this->data['item_ids'] = [$this->data['comment_entity_id']];
            } else {
                $and = 'list_id IS NOT NULL';
                if ($this->data['comment_in'] === 'list') {
                    $and = 'list_id = i:list_id';
                }
                /** @var pocketlistsItemModel $item_model */
                $item_model = pl2()->getModel(pocketlistsItem::class);
                $this->data['item_ids'] = $item_model->query("
                    SELECT id FROM pocketlists_item
                    WHERE key_list_id IS NULL AND $and
                    ORDER BY RAND()
                    LIMIT i:limit
                ", [
                    'list_id' => (int) $this->data['comment_entity_id'],
                    'limit'   => max(self::DEFAULT_COUNT, (int) $this->data['count_all'])
                ])->fetchAll();
                $this->data['item_ids'] = array_column($this->data['item_ids'], 'id');
            }
        }
        $rand = mt_rand(0, count($this->data['item_ids']) - 1);

        return ifset($this->data, 'item_ids', $rand, null);
    }

    /**
     * @param $entity_type
     * @return array|false|mixed|string
     * @throws waException
     */
    private function genName($entity_type)
    {
        static $names;
        static $count_name;
        if (empty($names)) {
            $config = wa()->getConfig();
            $file = $config->getPluginPath('gorshochek')."/lib/config/data/$entity_type.txt";
            if (file_exists($file)) {
                $names = file($file);
            } else {
                $names = [md5(microtime())];
            }
            $count_name = count($names);
        }
        $rand = mt_rand(0, $count_name - 1);
        switch ($entity_type) {
            case 'pocket':
            case 'list':
                return trim(ifset($names, $rand, md5(microtime()))).'-'.mt_rand(1, 5000);
            case 'item':
                // файл сверстан так, что четные идут на название, нечетные на заметку
                if ($rand % 2 === 1) {
                    $rand++;
                }
                return [
                    trim(ifset($names, $rand, md5(microtime()))),
                    trim(ifset($names, $rand + 1, md5(microtime())))
                ];
            case 'comment':
                return trim(ifset($names, $rand, md5(microtime())));
        }

        return reset($names);
    }

    /**
     * @param $fill
     * @return string|null
     */
    private function getUuid($fill)
    {
        $rand = mt_rand(0, 100);

        return ($rand % $fill === 0 ? waString::uuid() : null);
    }
}
