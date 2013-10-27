<?php

namespace Burgov\PredisWrapper;

class TypeFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')->disableOriginalConstructor()->getMock();
    }

    public function testInstantiateExpectSet()
    {
        $this->client->expects($this->once())->method('getType')->with('test_key')->will($this->returnValue('set'));

        $factory = new TypeFactory($this->client);
        $factory->instantiate('test_key');
    }

    public function testInstantiateSet()
    {
        $this->client->expects($this->once())->method('getType')->with('test_key')->will($this->returnValue('set'));

        $factory = new TypeFactory($this->client);
        $factory->instantiateSet('test_key');
    }

    /**
     * @expectedException Burgov\PredisWrapper\Exception\KeyDoesNotExistException
     */
    public function testInstantiateNonExistent()
    {
        $this->client->expects($this->once())->method('getType')->with('test_key')->will($this->returnValue('none'));

        $factory = new TypeFactory($this->client);
        $factory->instantiate('test_key');
    }
    /**
     * @expectedException Burgov\PredisWrapper\Exception\WrongTypeException
     */
    public function testInstantiateSetUnknownWithoutHint()
    {
        $this->client->expects($this->once())->method('getType')->with('test_key')->will($this->returnValue('flop'));

        $factory = new TypeFactory($this->client);
        $factory->instantiateSet('test_key');
    }
    /**
     * @expectedException Burgov\PredisWrapper\Exception\UnknownTypeException
     */
    public function testInstantiateUnknownWithoutHint()
    {
        $this->client->expects($this->once())->method('getType')->with('test_key')->will($this->returnValue('flop'));

        $factory = new TypeFactory($this->client);
        $factory->instantiate('test_key');
    }

}