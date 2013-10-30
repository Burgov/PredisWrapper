<?php

namespace Burgov\PredisWrapper\Type;


class Scalar extends AbstractType
{
    const
        NO_OVERWRITE = 'nx',
        ONLY_OVERWRITE = 'xx';

    // string functions

    public function set($value, $ttl = null, $overwriteFlag = null)
    {
        $arguments = array('set', $value);

        $ttlArgs = null;
        $overwriteArgs = null;

        switch (true) {
            case is_integer($ttl):
                $ttlArgs = array('EX', $ttl);
                break;
            case is_float($ttl):
                $ttlArgs = array('PX', floor($ttl * 1000));
                break;
            case null === $ttl:
                $ttlArgs = array();
                break;
            default:
                throw new \InvalidArgumentException(gettype($ttl));
        }

        if ($overwriteFlag !== null) {
            if ($overwriteFlag !== self::NO_OVERWRITE && $overwriteFlag !== self::ONLY_OVERWRITE) {
                throw new \InvalidArgumentException();
            }

            $overwriteArgs = array(strtoupper($overwriteFlag));
        } else {
            $overwriteArgs = array();
        }

        if (count($overwriteArgs) || count($ttlArgs)) {
            if (-1 == version_compare($this->getClient()->getVersion(), '2.6.12')) {
                // set with multiargs is not yet supported

                if (count($overwriteArgs)) {
                    if (strtolower($overwriteArgs[0]) == self::NO_OVERWRITE) {
                        if ($this->getClient()->exists($this)) {
                            return false;
                        }
                    } elseif (strtolower($overwriteArgs[0]) == self::ONLY_OVERWRITE) {
                        if (!$this->execute('exists')) {
                            return false;
                        }
                    }
                }

                if (count($ttlArgs)) {
                    $arguments[0] .= 'ex';
                    if ($ttlArgs[0] == 'PX') {
                        $arguments[0] = 'p'.$arguments[0];
                    }

                    array_splice($arguments, 1, 0, $ttlArgs[1]);
                }
            } else {
                $arguments = array_merge($arguments, $ttlArgs, $overwriteArgs);
            }
        }

        return (Boolean) call_user_func_array(array($this, 'execute'), $arguments);
    }

    public function get()
    {
        return $this->execute('get');
    }

    public function __toString()
    {
        return (string) $this->get();
    }

    public function append($value)
    {
        return (Boolean) $this->execute('append', $value);
    }

    public function getRange($start, $end)
    {
        return $this->execute('getrange', $start, $end);
    }

    public function setRange($start, $value)
    {
        return $this->execute('setrange', $start, $value);
    }

    public function getset($value)
    {
        return $this->execute('getset', $value);
    }

    public function getLength()
    {
        return $this->execute('strlen');
    }
} 