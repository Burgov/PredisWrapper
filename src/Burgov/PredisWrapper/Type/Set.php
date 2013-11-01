<?php

namespace Burgov\PredisWrapper\Type;


class Set extends AbstractListType
{
    /**
     * Wraps command SADD
     *
     * @param $element
     * @return bool
     */
    public function add($element)
    {
        return (Boolean)$this->execute('sadd', $element);
    }

    /**
     * Wraps command SREMOVE
     *
     * @param $element
     * @return bool
     */
    public function remove($element)
    {
        return (Boolean)$this->execute('sremove', $element);
    }

    /**
     * Wraps command SCARD
     *
     * @return mixed
     */
    public function count()
    {
        return $this->execute('scard');
    }

    private function multiSetFunction($function, array $arguments)
    {
        if (!count($arguments)) {
            throw new \BadMethodCallException('expected at least one argument');
        }

        foreach ($arguments as $key => $argument) {
            if (!$argument instanceof self) {
                throw new \BadMethodCallException('argument should be instance of ' . __CLASS__);
            }
        }

        $arguments = array_map(function(self $set) {
            return self::key($set);
        }, $arguments);

        array_unshift($arguments, sprintf('s%s', $function));
        return call_user_func_array(array($this, 'execute'), $arguments);
    }

    /**
     * Wraps command SDIFF
     *
     * @return mixed
     */
    public function diff()
    {
        return $this->multiSetFunction('diff', func_get_args());
    }

    /**
     * Wraps command SINTER
     *
     * @return mixed
     */
    public function intersect()
    {
        return $this->multiSetFunction('inter', func_get_args());
    }

    /**
     * Wraps command SUNION
     *
     * @return mixed
     */
    public function union()
    {
        return $this->multiSetFunction('union', func_get_args());
    }

    /**
     * Wraps command SISMEMBER
     *
     * @param $value
     * @return bool
     */
    public function contains($value)
    {
        return (Boolean)$this->execute('sismember', $value);
    }

    /**
     * Wraps command SMEMBERS
     *
     * @return mixed
     */
    public function all()
    {
        return $this->execute('smembers');
    }

    /**
     * Wraps command SMOVE
     *
     * @param Set $dest
     * @param $value
     * @return bool
     */
    public function move(self $dest, $value)
    {
        return (Boolean)$this->execute('smove', self::key($dest), $value);
    }

    /**
     * Wraps command SPOP
     *
     * @return bool
     */
    public function pop()
    {
        return (Boolean)$this->execute('spop');
    }

    /**
     * Wraps command SRANDMEMBER
     *
     * @return mixed
     */
    public function rand()
    {
        return $this->execute('srandmember');
    }

    /**
     * Wraps command SRANDMEMBER with position amount
     * @param $amount
     * @return mixed
     */
    public function randUniqueList($amount)
    {
        return $this->execute('srandmember', $amount);
    }

    /**
     * Wraps command SRANDMEMBER with negative amount
     * @param $amount
     * @return mixed
     */
    public function randList($amount)
    {
        return $this->execute('srandmember', -$amount);
    }

    /**
     * Wraps command SREM
     *
     * @param $value
     * @return bool
     */
    public function removeElement($value)
    {
        return (Boolean)$this->execute('srem', $value);
    }

    private static function createFromFunction($function, array $arguments)
    {
        $set = array_shift($arguments);

        foreach ($arguments as $key => $argument) {
            if (!$argument instanceof self) {
                throw new \BadMethodCallException('argument should be instance of ' . __CLASS__);
            }
        }

        $client = $arguments[0]->getClient();

        $arguments = array_map(function(self $set) {
            return self::key($set);
        }, $arguments);

        if (count($arguments) < 2) {
            throw new \BadMethodCallException('expected at least three arguments');
        }

        if (!$set instanceof self) {
            $set = new self($client, (string)$set);
        }


        array_unshift($arguments, sprintf('s%sstore', $function));
        call_user_func_array(array($set, 'execute'), $arguments);

        return $set;
    }

    /**
     * Wraps command SDIFFSTORE
     *
     * @return Set|mixed
     */
    public static function createFromDiff()
    {
        return self::createFromFunction('diff', func_get_args());
    }

    /**
     * Wraps command SINTERSTORE
     *
     * @return Set|mixed
     */
    public static function createFromIntersect()
    {
        return self::createFromFunction('inter', func_get_args());
    }

    /**
     * Wraps command SUNIONSTORE
     *
     * @return Set|mixed
     */
    public static function createFromUnion()
    {
        return self::createFromFunction('union', func_get_args());
    }
}