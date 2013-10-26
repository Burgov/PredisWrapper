<?php

namespace Burgov\PredisWrapper\Type;

use Burgov\PredisWrapper\Exception\HashKeyAlreadySetException;

class Hash extends AbstractType implements \ArrayAccess, \IteratorAggregate, \Countable
{
    public function offsetExists($offset)
    {
        return (Boolean)$this->execute('hexists', $offset);
    }

    public function offsetGet($offset)
    {
        return $this->execute('hget', $offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->execute('hset', $offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->execute('hdel', $offset);
    }

    public function toArray()
    {
        return $this->execute('hgetall');
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    public function increment($key, $value)
    {
        switch (true) {
            case is_integer($value):
                $command = 'hincrby';
                break;
            case is_float($value):
                $command = 'hincrbyfloat';
                break;
            default:
                throw new \InvalidArgumentException('Value should be integer or float, is ' . gettype($value));
        }

        $return = $this->execute($command, $key, $value);
        if (is_float($value)) {
            $return = (float)$return;
        }

        return $return;
    }

    public function keys()
    {
        return $this->execute('hkeys');
    }

    public function values()
    {
        return $this->execute('hvals');
    }

    public function count()
    {
        return $this->execute('hlen');
    }

    public function getKeyValues(array $keys)
    {
        return array_combine($keys, $this->execute('hmget', $keys));
    }

    public function setKeyValues(array $map)
    {
        $this->execute('hmset', $map);
    }

    public function trySet($key, $value)
    {
        if (!$this->execute('hsetnx', $key, $value)) {
            throw new HashKeyAlreadySetException();
        }
    }
}