<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Bart
 * Date: 22-10-13
 * Time: 19:43
 * To change this template use File | Settings | File Templates.
 */

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