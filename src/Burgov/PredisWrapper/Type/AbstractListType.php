<?php

namespace Burgov\PredisWrapper\Type;


use Predis\Client;

class AbstractListType extends AbstractType
{
    private static function formatSortResult(array $result, SortCriteria $sort)
    {
        $parts = $sort->getParts();
        if (!array_key_exists('GET', $parts)) {
            return $result;
        }
        if (count($parts['GET']) < 2) {
            return $result;
        }

        $keyedResults = array();
        $resultKey = 0;

        reset($result);
        $keys = $parts['GET'];
        do {
            $value = current($result);
            if (!array_key_exists($resultKey, $keyedResults)) {
                $keyedResults[$resultKey] = array();
            }
            $key = current($keys);

            $keyedResults[$resultKey][$key] = $value;

            if (false === next($keys)) {
                reset($keys);
                $resultKey++;
            }

        } while (next($result));

        return $keyedResults;
    }

    /**
     * Fetch the elements of a key sorted by SortCriteria
     * Wraps command SORT
     *
     * @see SortCriteria
     * @param SortCriteria $sort
     * @return array
     */
    public function sort(SortCriteria $sort = null)
    {
        if (null === $sort) {
            $sort = new SortCriteria();
        }
        $arguments = array('sort', $sort->getParts());

        return self::formatSortResult(call_user_func_array(array($this, "execute"), $arguments), $sort);
    }

    /**
     * Store the elements of a key sorted by SortCriteria into destination
     * Wraps method SORT with argument STORE
     *
     * @see SortCriteria
     * @param Set $dest
     * @Param Set $src
     * @param SortCriteria $sort
     * @return array
     */
    public static function createFromSort(Set $dest, Set $src, SortCriteria $sort = null)
    {
        if (null === $sort) {
            $sort = new SortCriteria();
        }
        $parts = $sort->getParts();
        $parts['STORE'] = self::key($dest);
        $arguments = array('sort', $parts);

        return self::formatSortResult(call_user_func_array(array($src, 'execute'), $arguments), $sort);
    }
}