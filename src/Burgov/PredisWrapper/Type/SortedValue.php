<?php

namespace Burgov\PredisWrapper\Type;


class SortedValue
{
    private $value;
    private $score;

    public function __construct($value, $score)
    {
        if (!is_numeric($score) || !is_scalar($value)) {
            throw new \InvalidArgumentException();
        }

        $this->value = $value;
        $this->score = $score;
    }

    public function __toString()
    {
        return (string)$this->getValue();
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getValue()
    {
        return $this->value;
    }
}
