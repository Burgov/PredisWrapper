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

    public function sort(SortCriteria $sort = null)
    {
        if (null === $sort) {
            $sort = new SortCriteria();
        }
        $arguments = array('sort', $sort->getParts());

        return self::formatSortResult(call_user_func_array(array($this, "execute"), $arguments), $sort);
    }

    public static function createFromSort(Set $dest, Set $src, SortCriteria $sort = null)
    {
        if (null === $sort) {
            $sort = new SortCriteria();
        }
        $parts = $sort->getParts();
        $parts['STORE'] = (string) $dest;
        $arguments = array('sort', $parts);

        return self::formatSortResult(call_user_func_array(array($src, 'execute'), $arguments), $sort);
    }
}