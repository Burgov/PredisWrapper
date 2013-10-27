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

    public function range($offset, $length)
    {
        return $this->execute('lrange', $offset, $length);
    }

    public function all()
    {
        return $this->range(0, -1);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    public function offsetExists($offset)
    {
        if (!is_integer($offset)) {
            throw new \InvalidArgumentException('PList only supports integer keys');
        }
        return count($this) > $offset;
    }

    public function offsetGet($offset)
    {
        if (!is_integer($offset)) {
            throw new \InvalidArgumentException('PList only supports integer keys');
        }
        return $this->execute('lindex', $offset);
    }

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

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('The unset method is not supported for PList');
    }

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

    public function push($value, $try = false)
    {
        return $this->execute('rpush' . ($try ? 'x' : ''), $value);
    }

    public function unshift($value, $try = false)
    {
        return $this->execute('lpush' . ($try ? 'x' : ''), $value);
    }

    public function pop($block = self::NON_BLOCK, $timeout = null)
    {
        $this->validateBlock($block, $timeout);

        if ($block === self::BLOCK) {
            return $this->blockPop($timeout);
        }

        return $this->execute('rpop');
    }

    public static function blockPopMulti(array $lists, $timeout = 0)
    {
        if (count($lists) < 1) {
            throw new \InvalidArgumentException();
        }

        $args = array_slice($lists, 1);
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


    public function shift($block = self::NON_BLOCK, $timeout = null)
    {
        $this->validateBlock($block, $timeout);

        if ($block === self::BLOCK) {
            return $this->blockShift($timeout);
        }

        return $this->execute('lpop');
    }

    public static function blockShiftMulti(array $lists, $timeout = 0)
    {
        if (count($lists) < 1) {
            throw new \InvalidArgumentException();
        }

        $args = array_slice($lists, 1);
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

    public function insert($value, $position, $dest)
    {
        if ($position !== self::BEFORE && $position !== self::AFTER) {
            throw new \InvalidArgumentException();
        }
        $this->execute('linsert', $position, $dest, $value);
    }

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

    public function trim($offset, $length)
    {
        return $this->execute('ltrim', $offset, $length);
    }

    public function popAndPushInto(self $dest, $block = self::NON_BLOCK, $timeout = null)
    {
        $this->validateBlock($block, $timeout);

        $args = array('rpoplpush', $dest);
        if ($block === self::BLOCK) {
            $args[0] = 'b' . $args[0];
            $args[] = $timeout;
        }

        return call_user_func_array(array($this, 'execute'), $args);
    }

    public function blockPopAndPushInto(self $dest, $timeout)
    {
        $this->popAndPushInto($dest, self::BLOCK, $timeout);
    }
}