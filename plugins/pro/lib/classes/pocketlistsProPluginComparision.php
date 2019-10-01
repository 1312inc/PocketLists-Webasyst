<?php

/**
 * Class pocketlistsProPluginComparision
 */
class pocketlistsProPluginComparision
{
    const TYPE_EQ       = '=';
    const TYPE_GT       = '>';
    const TYPE_GTE      = '>=';
    const TYPE_LT       = '<';
    const TYPE_LTE      = '<=';
    const TYPE_NQE      = '<>';
    const TYPE_CONTAINS = 'contains';

    /**
     * @param mixed  $val
     * @param mixed  $val2
     * @param string $type
     *
     * @return bool
     * @throws pocketlistsLogicException
     */
    public static function compare($val, $val2, $type)
    {
        switch ($type) {
            case self::TYPE_EQ:
                return $val == $val2;

            case self::TYPE_GT:
                return $val > $val2;

            case self::TYPE_GTE:
                return $val >= $val2;

            case self::TYPE_LT:
                return $val < $val2;

            case self::TYPE_LTE:
                return $val <= $val2;

            case self::TYPE_NQE:
                return $val != $val2;

            case self::TYPE_CONTAINS:
                if (is_array($val2)) {
                    return in_array($val, $val2);
                }

                return mb_strpos($val2, $val) !== false;
        }

        throw new pocketlistsLogicException(sprintf('Unknown comparision type %s', $type));
    }
}
