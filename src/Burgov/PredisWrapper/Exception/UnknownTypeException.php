<?php

namespace Burgov\PredisWrapper\Exception;

class UnknownTypeException extends \LogicException
{

    public function __construct($key, $type)
    {
        parent::__construct(sprintf("Key '%s' has unknown type '%s'", $key, $type));
    }
}