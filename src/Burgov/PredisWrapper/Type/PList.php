<?php

namespace Burgov\PredisWrapper\Type;


use Traversable;

class PList extends AbstractListType implements \ArrayAccess, \Countable, \IteratorAggregate
{
    const
        BLOCK = 'block',
        NON_BLOCK = 'non_block',
        BEFORE = 'before',
        AFTER = 'after',
        HEAD_TO_TAIL = 'head_to_tail',
        TAIL_TO_HEAD = 'tail_to_head';

    /**
     * Wraps command LRANGE
     *
     * @param $offset
     * @param $length
     * @return array
     */
    public function range($offset, $length)
    {
        return $this->execute('lrange', $offset, $length);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->range(0, -1);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @param int $offset
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function offsetExists($offset)
    {
        if (!is_integer($offset)) {
            throw new \InvalidArgumentException('PList only supports integer keys');
        }
        return count($this) > $offset;
    }

    /**
     * Wraps command LINDEX
     *
     * @param int $offset
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function offsetGet($offset)
    {
        if (!is_integer($offset)) {
            throw new \InvalidArgumentException('PList only supports integer keys');
        }
        return $this->execute('lindex', $offset);
    }

    /**
     * Wraps command LSET
     *
     * @param int $offset
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->push($value);
            return;
        }
        if (!is_integer($offset)) {
            throw new \InvalidArgumentException('PList only supports integer keys');
        }
        $this->execute('lset', $offset, $value);
    }

    /**
     * @param mixed $offset
     * @throws \BadMethodCallException
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('The unset method is not supported for PList');
    }

    /**
     * Wraps command LLEN
     *
     * @return int
     */
    public function count()
    {
        return $this->execute('llen');
    }

    private function validateBlock($block, &$timeout)
    {
        if ($block !== self::BLOCK && $block !== self::NON_BLOCK) {
            throw new \InvalidArgumentException();
        }
        if ($block === self::NON_BLOCK) {
            if ($timeout !== null) {
                throw new \BadMethodCallException('Timeout can only be supplied for blocking operations');
            }
        } else {
            $timeout = (int)$timeout;
        }
    }

    /**
     * Wraps commands RPUSH and RPUSHX
     *
     * @param $value
     * @param bool $try
     * @return mixed
     */
    public function push($value, $try = false)
    {
        return $this->execute('rpush' . ($try ? 'x' : ''), $value);
    }

    /**
     * Wraps commands LPUSH and LPUSHX
     *
     * @param $value
     * @param bool $try
     * @return mixed
     */
    public function unshift($value, $try = false)
    {
        return $this->execute('lpush' . ($try ? 'x' : ''), $value);
    }

    /**
     * Wraps command RPOP
     * @param string $block
     * @param null $timeout
     * @return mixed
     */
    public function pop($block = self::NON_BLOCK, $timeout = null)
    {
        $this->validateBlock($block, $timeout);

        if ($block === self::BLOCK) {
            return $this->blockPop($timeout);
        }

        return $this->execute('rpop');
    }

    /**
     * Wraps command BRPOP
     *
     * @param array $lists
     * @param int $timeout
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function blockPopMulti(array $lists, $timeout = 0)
    {
        if (count($lists) < 1) {
            throw new \InvalidArgumentException();
        }

        $args = array_map(function(self $list) {
            return self::key($list);
        }, array_slice($lists, 1));

        array_unshift($args, 'brpop');
        $args[] = $timeout;

        $res = call_user_func_array(array($lists[0], 'execute'), $args);
        if (is_array($res)) {
            $res = array($res[0] => $res[1]);
        }

        return $res;
    }

    public function blockPop($timeout)
    {
        return self::blockPopMulti(array($this), $timeout);
    }

    /**
     * Wraps command LPOP
     *
     * @param string $block
     * @param null $timeout
     * @return mixed
     */
    public function shift($block = self::NON_BLOCK, $timeout = null)
    {
        $this->validateBlock($block, $timeout);

        if ($block === self::BLOCK) {
            return $this->blockShift($timeout);
        }

        return $this->execute('lpop');
    }

    /**
     * Wraps command BLPOP
     *
     * @param array $lists
     * @param int $timeout
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function blockShiftMulti(array $lists, $timeout = 0)
    {
        if (count($lists) < 1) {
            throw new \InvalidArgumentException();
        }

        $args = array_map(function(self $list) {
            return self::key($list);
        }, array_slice($lists, 1));

        array_unshift($args, 'blpop');
        $args[] = $timeout;

        $res = call_user_func_array(array($lists[0], 'execute'), $args);
        if (is_array($res)) {
            $res = array($res[0] => $res[1]);
        }

        return $res;
    }

    public function blockShift($timeout)
    {
        return self::blockShiftMulti(array($this), $timeout);
    }

    /**
     * Wraps command LINSERT
     *
     * @param $value
     * @param $position
     * @param $dest
     * @throws \InvalidArgumentException
     */
    public function insert($value, $position, $dest)
    {
        if ($position !== self::BEFORE && $position !== self::AFTER) {
            throw new \InvalidArgumentException();
        }
        $this->execute('linsert', $position, $dest, $value);
    }

    /**
     * Wraps command LREM
     *
     * @param $value
     * @param int $count
     * @param string $direction
     * @throws \InvalidArgumentException
     */
    public function remove($value, $count = 0, $direction = self::HEAD_TO_TAIL)
    {
        if ($direction !== self::HEAD_TO_TAIL && $direction !== self::TAIL_TO_HEAD) {
            throw new \InvalidArgumentException();
        }
        if ($direction === self::TAIL_TO_HEAD) {
            $count *= -1;
        }
        $this->execute('lrem', $count, $value);
    }

    /**
     * Wraps command LTRIM
     *
     * @param $offset
     * @param $length
     * @return mixed
     */
    public function trim($offset, $length)
    {
        return $this->execute('ltrim', $offset, $length);
    }

    /**
     * Wraps commands RPOPLPUSH and BRPOPLPUSH
     *
     * @param PList $dest
     * @param string $block
     * @param null $timeout
     * @return mixed
     */
    public function popAndPushInto(self $dest, $block = self::NON_BLOCK, $timeout = null)
    {
        $this->validateBlock($block, $timeout);

        $args = array('rpoplpush', self::key($dest));
        if ($block === self::BLOCK) {
            $args[0] = 'b' . $args[0];
            $args[] = $timeout;
        }

        return call_user_func_array(array($this, 'execute'), $args);
    }

    /**
     * Wraps command BRPOPLPUSH
     *
     * @param PList $dest
     * @param $timeout
     */
    public function blockPopAndPushInto(self $dest, $timeout)
    {
        $this->popAndPushInto($dest, self::BLOCK, $timeout);
    }
}