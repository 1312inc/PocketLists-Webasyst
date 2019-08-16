<?php

/**
 * Class pocketlistsModel
 */
class pocketlistsModel extends waModel
{
    /**
     * pocketlistsModel constructor.
     *
     * @param null $type
     * @param bool $writable
     */
    public function __construct($type = null, $writable = false)
    {
        parent::__construct($type, $writable);

        try {
            $this->exec('set names utf8mb4');
        } catch (Exception $ex) {
            waLog::log('PLEASE UPDATE YOUR MYSQL DATABASE. ' . $ex->getMessage());
        }
    }

    /**
     * @todo: вынести в отдельный сервис
     * @param array $query
     * @param int   $limit
     * @param int   $offset
     *
     * @return string
     */
    public function buildSqlComponents(array $query, $limit = 0, $offset = 0)
    {
        $q = sprintf(
            '
            select %s
            from %s
            %s
            %s
            %s
            %s
            %s',
            implode(",\n", $query['select']),
            implode(",\n", $query['from']),
            implode("\n", $query['join']),
            !empty($query['where']['and'])
                ? 'where '.implode(' AND ', $query['where']['and'])
                : '',
            !empty($query['group by'])
                ? 'group by '.implode(",\n", $query['group by'])
                : '',
            !empty($query['order by'])
                ? 'order by '.implode(",\n", $query['order by'])
                : '',
            $limit || $offset ? sprintf('limit %d, %d', $offset, $limit) : ''
        );

        return $q;
    }
}
