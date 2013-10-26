<?php
/**
 * Created by PhpStorm.
 * User: Bart
 * Date: 26-10-13
 * Time: 18:00
 */

namespace Burgov\PredisWrapper\Type;


class SortCriteria
{
    const
        ASC = 'ASC',
        DESC = 'DESC',
        GET_SELF = '#';

    private $by;

    private $limit = array();

    private $get = array();

    private $direction = self::ASC;

    private $alpha = false;

    public function __construct($by = null, $limit = null, $get = null, $direction = self::ASC, $alpha = false)
    {
        $this->setBy($by);
        $this->setLimit($limit);
        $this->setGet($get);
        $this->setDirection($direction);
        $this->setAlpha($alpha);
    }

    private function setBy($by)
    {
        if (null === $by) {
            return;
        }
        if (false === $by) {
            $by = 'lets_assume_this_key_does_not_exist';
        }
        $this->by = (string)$by;
    }

    private function setLimit($limit)
    {
        if (null === $limit) {
            return;
        }
        $exception = new \InvalidArgumentException('The passed argument should be a string containing two numbers separated by a space or an array containing two integers');

        if (is_string($limit)) {
            if (!preg_match('/^\d+ \d+$/', $limit)) {
                throw $exception;
            }

            $limit = explode(" ", $limit);
        }

        if (!is_array($limit)) {
            throw $exception;
        }

        if (!count($limit) == 2) {
            throw $exception;
        }

        $this->limit = $limit;
    }

    private function setGet($get)
    {
        if (null === $get) {
            $this->get = array();
            return;
        }
        if (is_string($get)) {
            $get = array($get);
        }
        if (!is_array($get)) {
            throw new \InvalidArgumentException('The passed argument should be a string or an array of strings');
        }

        $this->get = $get;
    }

    private function setDirection($direction)
    {
        $direction = strtoupper($direction);
        if ($direction !== self::ASC && $direction !== self::DESC) {
            throw new \InvalidArgumentException('The argument should be one of "asc" or "desc"');
        }

        $this->direction = $direction;
    }

    private function setAlpha($alpha)
    {
        if (!is_bool($alpha)) {
            throw new \InvalidArgumentException('The argument should be a Boolean value');
        }

        $this->alpha = $alpha;
    }

    public function getParts()
    {
        $parts = array();

        if ($this->by) {
            $parts['BY'] = $this->by;
        }
        if ($this->limit) {
            $parts['LIMIT'] = $this->limit;
        }
        if (count($this->get)) {
            $parts['GET'] = $this->get;
        }
        if ($this->direction != self::ASC) {
            $parts['SORT'] = $this->direction;
        }
        if ($this->alpha) {
            $parts['ALPHA'] = 'ALPHA';
        }

        return $parts;
    }
} 