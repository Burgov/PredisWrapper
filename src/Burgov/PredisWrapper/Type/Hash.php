<?php

namespace Burgov\PredisWrapper\Type;

use Burgov\PredisWrapper\Exception\HashKeyAlreadySetException;

class Hash extends AbstractType implements \ArrayAccess, \IteratorAggregate, \Countable
{

    /**
     * Wraps command HEXISTS
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return (Boolean)$this->execute('hexists', $offset);
    }

    /**
     * Wraps command HGET
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->execute('hget', $offset);
    }

    /**
     * Wraps command HSET
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->execute('hset', $offset, $value);
    }

    /**
     * Wraps command HDEL
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->execute('hdel', $offset);
    }

    /**
     * Wraps command HGETALL
     *
     * @return array
     */
    public function toArray()
    {
        return $this->execute('hgetall');
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * Increment the value stored at key by given amount
     * Wraps commands HINCRBY (if value is integer) and HINCRBYFLOAT (if value is float)
     *
     * @param string $key
     * @param int|float $value
     * @return int|float
     * @throws \InvalidArgumentException
     */
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

    /**
     * Get all the keys in the hash
     * Wraps command HKEYS
     *
     * @return array
     */
    public function keys()
    {
        return $this->execute('hkeys');
    }

    /**
     * Get all the values in the hash
     * Wraps command HVALS
     *
     * @return array
     */
    public function values()
    {
        return $this->execute('hvals');
    }

    /**
     * Wraps command HLEN
     *
     * @return int
     */
    public function count()
    {
        return $this->execute('hlen');
    }

    /**
     * Get all the values for the specified keys
     * Wraps command HMGET
     *
     * @param array $keys
     * @return array
     */
    public function getKeyValues(array $keys)
    {
        return array_combine($keys, $this->execute('hmget', $keys));
    }

    /**
     * Set all the values specified by keys in the map
     * Wraps command HMSET
     *
     * @param array $map
     */
    public function setKeyValues(array $map)
    {
        $this->execute('hmset', $map);
    }

    /**
     * Set the key to specified value or fail if the key already exists
     * Wraps command HSETNX
     *
     * @param $key
     * @param $value
     * @throws \Burgov\PredisWrapper\Exception\HashKeyAlreadySetException
     */
    public function trySet($key, $value)
    {
        if (!$this->execute('hsetnx', $key, $value)) {
            throw new HashKeyAlreadySetException();
        }
    }
}
