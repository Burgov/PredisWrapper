<?php

namespace Burgov\PredisWrapper\Type;


class SortedSet extends AbstractListType implements \Countable, \IteratorAggregate
{
    const
        AGGREGATE_SUM = 'sum',
        AGGREGATE_MIN = 'min',
        AGGREGATE_MAX = 'max',

        REVERSE = 1,
        BY_SCORE = 2,
        WITH_SCORES = 4;

    private function checkRange(&$start, &$end)
    {
        if ($start === null || $start === -INF) {
            $start = '-inf';
        }
        if ($end === null || $end === INF) {
            $end = 'inf';
        }
    }

    /**
     * Wraps command ZCARD and ZCOUNT
     *
     * @param null $start
     * @param null $end
     * @return int|mixed
     */
    public function count($start = null, $end = null)
    {
        if (null === $start && null === $end) {
            return $this->execute('zcard');
        }

        $this->checkRange($start, $end);

        return $this->execute('zcount', $start, $end);
    }

    /**
     * Wraps command ZADD
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function add()
    {
        $args = array();

        foreach (func_get_args() as $arg) {
            if (!$arg instanceof SortedValue) {
                throw new \InvalidArgumentException;
            }

            $args[] = $arg->getScore();
            $args[] = $arg->getValue();
        }

        array_unshift($args, 'zadd');
        return call_user_func_array(array($this, 'execute'), $args);
    }

    /**
     * Wraps command ZREM
     *
     * @param $arg1
     * @return mixed
     */
    public function remove($arg1)
    {
        $args = func_get_args();
        array_unshift($args, 'zrem');
        return call_user_func_array(array($this, 'execute'), $args);
    }

    /**
     * Wraps commands ZRANK and ZREVRANK
     *
     * Pass SortedSet::REVERSE as $flags argument to use ZREVRANK
     *
     * @param $value
     * @param null $flags
     * @return mixed
     */
    public function getRank($value, $flags = null)
    {
        $command = 'z';
        if ($flags & self::REVERSE) {
            $command .= 'rev';
        }
        $command .= 'rank';
        return $this->execute($command, $value);
    }

    /**
     * Wraps command ZSCORE
     *
     * @param $value
     * @return mixed
     */
    public function getScore($value)
    {
        return $this->execute('zscore', $value);
    }

    /**
     * Wraps command ZINCRBY
     *
     * @param $value
     * @param $score
     * @return mixed
     */
    public function incrementScore($value, $score)
    {
        return $this->execute('zincrby', $score, $value);
    }

    private static function createFromFunction($function, array $arguments)
    {
        list($dest, $from, $weights, $aggregate) = $arguments;

        if (count($from) < 2) {
            throw new \InvalidArgumentException('Supply at least two SortedSets to create a union from');
        }
        foreach ($from as $sortedSet) {
            if (!$sortedSet instanceof self) {
                throw new \InvalidArgumentException;
            }
        }

        if (!in_array($aggregate, array(self::AGGREGATE_MIN, self::AGGREGATE_MAX, self::AGGREGATE_SUM))) {
            throw new \InvalidArgumentException;
        }

        if (!$dest instanceof self) {
            $dest = new self($from[0]->getClient(), (string)$dest);
        }

        $arguments = array_map(function (self $set) {
            return self::key($set);
        }, $from);

        array_unshift($arguments, count($arguments));
        array_unshift($arguments, 'z' . $function . 'store');
        if (null !== $weights) {
            $b = count($from);
            for ($a = count($weights); $a < $b; $a++) {
                $weights[] = 1;
            }
            $arguments[] = 'WEIGHTS';
            foreach ($weights as $weight) {
                $arguments[] = (string)$weight;
            }
        }

        if ($aggregate !== self::AGGREGATE_SUM) {
            $arguments[] = 'AGGREGATE';
            $arguments[] = strtoupper($aggregate);
        }

        call_user_func_array(array($dest, 'execute'), $arguments);
        return $dest;
    }

    /**
     * Wraps command ZUNIONSTORE
     *
     * @param $dest
     * @param array $from
     * @param array $weights
     * @param string $aggregate
     * @return SortedSet
     */
    public static function createFromUnion($dest, array $from, array $weights = null, $aggregate = self::AGGREGATE_SUM)
    {
        return self::createFromFunction('union', func_get_args());
    }

    /**
     * Wraps command ZINTERSTORE
     *
     * @param $dest
     * @param array $from
     * @param array $weights
     * @param string $aggregate
     * @return SortedSet
     */
    public static function createFromIntersect(
        $dest,
        array $from,
        array $weights = null,
        $aggregate = self::AGGREGATE_SUM
    ) {
        return self::createFromFunction('inter', func_get_args());
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getRange());
    }

    /**
     * Wraps commands ZRANGE, ZREVRANGE, ZRANGEBYSCORE and ZREVRANGEBYSCORE
     *
     * To call ZRANGE: $sortedSet->getRange(0, -1);
     * To call ZREVRANGE: $sortedSet->getRange(0, -1, SortedSet::REVERSE)
     * To call ZRANGEBYSCORE: $sortedSet->getRange(0, -1, SortedSet::BY_SCORE)
     * To call ZREVRANGEBYSCORE: $sortedSet->getRange(0, -1, SortedSet::REVERSE | SortedSet::BY_SCORE)
     * To call ZREVRANGEBYSCORE + WITHSCORES + LIMIT: $sortedSet->getRange(0, -1,
     *      SortedSet::REVERSE | SortedSet::BY_SCORE | SortedSet::WITH_SCORES, array(1, 3)
     * )
     *
     * @param int $start
     * @param $end
     * @param int $flags
     * @param array $limit
     * @return array|mixed
     * @throws \InvalidArgumentException
     */
    public function getRange($start = 0, $end = -1, $flags = 0, array $limit = null)
    {
        $command = 'z';

        if ($flags & self::REVERSE) {
            $command .= 'rev';
        }

        $command .= 'range';

        if ($flags & self::BY_SCORE) {
            $command .= "byscore";
        }

        $arguments = array($command, $start, $end);
        if ($flags & self::WITH_SCORES) {
            $arguments[] = 'withscores';
        }

        if (null !== $limit) {
            if (!($flags & self::BY_SCORE)) {
                throw new \InvalidArgumentException('Can only set limit when fetching by score');
            }
            if (count($limit) != 2) {
                throw new \InvalidArgumentException('Limit should be array of two integers');
            }

            $arguments = array_merge($arguments, array('limit', $limit[0], $limit[1]));
        }

        $results = call_user_func_array(array($this, 'execute'), $arguments);

        if ($flags & self::WITH_SCORES) {
            $results = array_map(function (array $result) {
                return new SortedValue($result[0], $result[1]);
            }, $results);
        }

        return $results;
    }

    /**
     * Wraps commands ZREMRANGEBYSCORE and ZREMRANGEBYRANK
     *
     * Pass SortedSet::BY_SCORE as $flags to use ZREMRANKBYSCORE
     *
     * @param int $start
     * @param $end
     * @param int $flags
     * @return mixed
     */
    public function removeRange($start = 0, $end = -1, $flags = 0)
    {
        $command = 'zremrangeby';

        if ($flags & self::BY_SCORE) {
            $command .= "score";
        } else {
            $command .= 'rank';
        }

        $arguments = array($command, $start, $end);

        return call_user_func_array(array($this, 'execute'), $arguments);
    }
}
