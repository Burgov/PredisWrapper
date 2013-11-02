<?php

namespace Burgov\PredisWrapper\Type;


class AbstractTypeTest extends \PHPUnit_Framework_TestCase
{

    public function testCallMethod()
    {
        $client = $this->getMockBuilder('Burgov\PredisWrapper\Client')->disableOriginalConstructor()->getMock();

        $type = $this->getMockBuilder('Burgov\PredisWrapper\Type\AbstractType')
            ->setConstructorArgs(array($client, 'test_key'))
            ->setMethods(null)
            ->getMock();

        $client->expects($this->once())->method('__call')->with('zadd', array('test_key', 15, 'test'));
        $type->execute('zadd', 15, 'test');
    }
}
