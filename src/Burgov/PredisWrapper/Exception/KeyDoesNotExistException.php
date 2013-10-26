<?php

namespace Burgov\PredisWrapper\Exception;

class KeyDoesNotExistException extends \LogicException
{

    public function __construct($key)
    {
        parent::__construct(sprintf("Key %s does not exist", $key));
    }
}