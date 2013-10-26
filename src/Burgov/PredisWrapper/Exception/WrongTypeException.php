<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Bart
 * Date: 23-10-13
 * Time: 22:24
 * To change this template use File | Settings | File Templates.
 */

namespace Burgov\PredisWrapper\Exception;


class WrongTypeException extends \LogicException
{
    public function __construct($expected, $actual)
    {
        parent::__construct(sprintf("Expected %s, got %s", $expected, $actual));
    }
}