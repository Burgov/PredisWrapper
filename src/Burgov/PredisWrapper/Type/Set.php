<?php

namespace Burgov\PredisWrapper\Type;


class Set extends AbstractListType
{
    public function add($element)
    {
        return (Boolean)$this->execute('sadd', $element);
    }

    public function remove($element)
    {
        return (Boolean)$this->execute('sremove', $element);
    }

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

    public function diff()
    {
        return $this->multiSetFunction('diff', func_get_args());
    }

    public function intersect()
    {
        return $this->multiSetFunction('inter', func_get_args());
    }

    public function union()
    {
        return $this->multiSetFunction('union', func_get_args());
    }

    public function contains($value)
    {
        return (Boolean)$this->execute('sismember', $value);
    }

    public function all()
    {
        return $this->execute('smembers');
    }

    public function move(self $dest, $value)
    {
        return (Boolean)$this->execute('smove', self::key($dest), $value);
    }

    public function pop()
    {
        return (Boolean)$this->execute('spop');
    }

    public function rand()
    {
        return $this->execute('srandmember');
    }

    public function randUniqueList($amount)
    {
        return $this->execute('srandmember', $amount);
    }

    public function randList($amount)
    {
        return $this->execute('srandmember', -$amount);
    }

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

    public static function createFromDiff()
    {
        return self::createFromFunction('diff', func_get_args());
    }

    public static function createFromIntersect()
    {
        return self::createFromFunction('inter', func_get_args());
    }

    public static function createFromUnion()
    {
        return self::createFromFunction('union', func_get_args());
    }
}