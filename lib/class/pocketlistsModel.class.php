<?php

/**
 * Class pocketlistsModel
 */
class pocketlistsModel extends waModel
{
    /** @var int */
    private $autocommit_mode = 0;

    /**
     * pocketlistsModel constructor.
     *
     * @param $type
     * @param $writable
     * @throws waDbException
     * @throws waException
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
     * @param int $flag
     */
    public function autocommit($flag)
    {
        $this->exec('SET autocommit = '.(int) $flag);
    }

    public function startTransaction()
    {
        $this->autocommit_mode = $this->query('SELECT @@autocommit')->fetchField();
        $this->autocommit(0);
        $this->exec('start transaction');
    }

    public function commit()
    {
        $this->exec('commit');
        $this->autocommit($this->autocommit_mode);
    }

    public function rollback()
    {
        $this->exec('rollback');
        $this->autocommit($this->autocommit_mode);
    }

    /**
     * @param array $query
     * @param int   $limit
     * @param int   $offset
     * @param bool  $calcFoundRows
     *
     * @return string
     * @todo: вынести в отдельный сервис
     */
    public function buildSqlComponents(array $query, $limit = 0, $offset = 0, $calcFoundRows = false)
    {
        $where = [];

        if (!empty($query['where']['and'])) {
            $where[] = '('.implode(') and (', $query['where']['and']).')';
        }
        if (!empty($query['where']['or'])) {
            $where[] = '('.implode(') or (', $query['where']['or']).')';
        }

        if ($where) {
            $where = ' where ' . implode(' or ', $where);
        } else {
            $where = '';
        }

        $q = sprintf('
            select %s %s
            from %s
            %s
            %s
            %s
            %s
            %s',
            $calcFoundRows ? ' SQL_CALC_FOUND_ROWS ' : '',
            implode(",\n", $query['select']),
            implode(",\n", $query['from']),
            implode("\n", $query['join']),
            $where,
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
