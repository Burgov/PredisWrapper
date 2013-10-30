<?php

namespace Burgov\PredisWrapper\Type;


class HashTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $type;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')->disableOriginalConstructor()->getMock();

        $this->type = $this->getMockBuilder('Burgov\PredisWrapper\Type\Hash')
            ->setConstructorArgs(array($this->client, 'test_key'))
            ->setMethods(null)
            ->getMock();
    }

    public function testSet()
    {
        $this->client->expects($this->once())->method('__call')->with('hset', array('test_key', 'key', 'value'));
        $this->type['key'] = 'value';
    }

    public function testUnset()
    {
        $this->client->expects($this->once())->method('__call')->with('hdel', array('test_key', 'key'));
        unset($this->type['key']);
    }

    public function testIsset()
    {
        $this->client->expects($this->once())->method('__call')->with('hexists', array('test_key', 'key'));
        isset($this->type['key']);
    }

    public function testGet()
    {

        $this->client->expects($this->once())->method('__call')->with('hget', array('test_key', 'key'));
        $this->type['key'];
    }

    public function testIterate()
    {
        $this->client->expects($this->once())->method('__call')->with('hgetall', array('test_key'))->will($this->returnValue(array('key1' => 'value1', 'key2' => 'value2')));
        $this->assertEquals(array('key1' => 'value1', 'key2' => 'value2'), iterator_to_array($this->type));
    }

    public function testIncrement()
    {
        $this->client->expects($this->once())->method('__call')->with('hincrby', array('test_key', 'key', 3))->will($this->returnValue(4));
        $ret = $this->type->increment('key', 3);

        $this->assertInternalType('integer', $ret);
    }

    public function testIncrementByFloat()
    {
        $this->client->expects($this->once())->method('__call')->with('hincrbyfloat', array('test_key', 'key', 3.5))->will($this->returnValue('8.75'));
        $ret = $this->type->increment('key', 3.5);

        $this->assertInternalType('float', $ret);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncrementByString()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type->increment('key', '4');
    }

    public function testKeys()
    {
        $this->client->expects($this->once())->method('__call')->with('hkeys', array('test_key'));
        $this->type->keys();
    }

    public function testValues()
    {
        $this->client->expects($this->once())->method('__call')->with('hvals', array('test_key'));
        $this->type->values();
    }

    public function testCount()
    {
        $this->client->expects($this->once())->method('__call')->with('hlen', array('test_key'));
        count($this->type);
    }

    public function testGetKeyValues()
    {
        $this->client->expects($this->once())->method('__call')->with('hmget', array('test_key', array('key1', 'key2')))->will($this->returnValue(array('value1', 'value2')));
        $this->assertEquals(array('key1' => 'value1', 'key2' => 'value2'), $this->type->getKeyValues(array('key1', 'key2')));
    }

    public function testSetKeyValues()
    {
        $this->client->expects($this->once())->method('__call')->with('hmset', array('test_key', array('key1' => 'value1', 'key2' => 'value2')));
        $this->type->setKeyValues(array('key1' => 'value1', 'key2' => 'value2'));
    }

    public function testTrySet()
    {
        $this->client->expects($this->once())->method('__call')->with('hsetnx', array('test_key', 'key', 'value'))->will($this->returnValue(1));
        $this->type->trySet('key', 'value');
    }

    /**
     * @expectedException Burgov\PredisWrapper\Exception\HashKeyAlreadySetException
     */
    public function testTrySetFail()
    {
        $this->client->expects($this->once())->method('__call')->with('hsetnx', array('test_key', 'key', 'value'))->will($this->returnValue(0));
        $this->type->trySet('key', 'value');
    }
}