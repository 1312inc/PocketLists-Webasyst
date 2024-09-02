<?php

final class pocketlistsSortRank
{
    const MAX_RANK_LEN = 8;

    const COLLECTION = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    private static pocketlistsSortRank $instance;

    private int $sort;

    private array $matrix;

    private array $collection;

    private int $count_chars;

    private function __construct()
    {
        $this->collection = self::getCollection();
        $this->count_chars = count($this->collection);
    }

    /**
     * @return pocketlistsSortRank
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param int $sort
     * @param string $rank
     * @return void
     * @throws Exception
     */
    public function new($sort, $rank)
    {
        if (!is_integer($sort)) {
            throw new Exception('Sort is not an integer type');
        } elseif (!is_string($rank)) {
            throw new Exception('Sort is not an string type');
        }
        $rank = (empty($rank) ? reset($this->collection) : $rank);
        $this->sort = $sort;
        $this->matrix = $this->convertToMatrix($rank);
        if (count($this->matrix) > self::MAX_RANK_LEN) {
            throw new Exception('The length of Rank provided is too long. Rank Provided: ' . $rank . ' - Rank Length: ' . count($this->matrix) . ' - Max length: ' . self::MAX_RANK_LEN);
        }
    }

    /**
     * @return array
     */
    public static function getCollection()
    {
        $c = str_split(self::COLLECTION);
        sort($c);

        return $c;
    }


    public static function rankValidate($rank)
    {
        if (!is_string($rank) || trim($rank) === '') {
            return false;
        }

        $chars = str_split($rank);
        if (count($chars) > self::MAX_RANK_LEN) {
            return false;
        }
        $collection = self::getCollection();
        foreach ($chars as $_char) {
            if (!in_array($_char, $collection, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function get()
    {
        return [$this->sort, $this->convertToRank($this->matrix)];
    }

    /**
     * @return array
     */
    public function previous()
    {
        $this->sort--;

        return [$this->sort, $this->convertToRank($this->matrix)];
    }

    /**
     * @return array
     */
    public function next()
    {
        $this->sort++;

        return [$this->sort, $this->convertToRank($this->matrix)];
    }

    /**
     * @param $next_sort
     * @param $next_rank
     * @return array
     * @throws Exception
     */
    public function between($next_sort, $next_rank)
    {
        if (!is_integer($next_sort)) {
            throw new Exception('Sort is not an integer type');
        } elseif (!is_string($next_rank)) {
            throw new Exception('Sort is not an string type');
        }
        if ($next_sort != $this->sort) {
            $next_sort -= ceil(($next_sort - $this->sort) / 2);
        }
        $current_matrix = array_reverse($this->matrix);
        $next_matrix = array_reverse($this->convertToMatrix($next_rank));
        $count = max(count($next_matrix), count($current_matrix));
        $difference = [];
        for ($i = 0; $i < $count; $i++) {
            $_l = ifset($current_matrix, $i, null);
            $_r = ifset($next_matrix, $i, null);
            if ($_l === $_r) {
                $difference[] = reset($this->collection);
            } elseif (!isset($_l, $_r)) {
                $difference[] = (int) ceil($this->count_chars - (is_null($_l) ? $_r : $_l) / 2);
            } else {
                $difference[] = (int) ceil(($_l - $_r) / 2);
            }
        }

        $next_matrix = [];
        foreach ($difference as $_i => $_diff) {
            $next_matrix[] = (int) ifset($current_matrix, $_i, $this->count_chars) - $_diff;
        }

        return [$next_sort, $this->convertToRank(array_reverse($next_matrix))];
    }

    /**
     * @param $rank
     * @return array
     * @throws Exception
     */
    private function convertToMatrix($rank)
    {
        $matrix = [];
        $chars = (is_string($rank) ? str_split($rank) : '');
        if (!empty($chars)) {
            for ($i = count($chars) - 1; $i >= 0; $i--) {
                $_char = array_pop($chars);
                $pos = array_search($_char, $this->collection, true);
                if ($pos === false) {
                    throw new Exception('Rank provided contains an invalid Char. Invalid char: '.$_char);
                }
                $matrix[] = $pos;
            }
        }

        return $matrix;
    }

    /**
     * @param $matrix
     * @return string
     */
    private function convertToRank($matrix = [])
    {
        $rank = [];
        if (count($matrix)) {
            $pos = reset($matrix);
            do {
                $rank[] = ifset($this->collection, $pos, '');
                $pos = next($matrix);
            } while ($pos !== false);
        }

        return ($rank === [] ? reset($this->collection) : implode('', array_reverse($rank)));
    }
}
