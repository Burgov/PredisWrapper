<?php

namespace Burgov\PredisWrapper\Type;


class PListTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $type;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Predis\Client')->disableOriginalConstructor()->getMock();

        $this->type = $this->getMockBuilder('Burgov\PredisWrapper\Type\PList')
            ->setConstructorArgs(array($this->client, 'test_key'))
            ->setMethods(null)
            ->getMock();
    }

    public function testSet()
    {
        $this->client->expects($this->once())->method('__call')->with('lset', array('test_key', 15, 'value'));
        $this->type[15] = 'value';
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetAlpha()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type['a'] = 'value';
    }

    public function testGet()
    {
        $this->client->expects($this->once())->method('__call')->with('lindex', array('test_key', 15));
        $this->type[15];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetAlpha()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type['a'];
    }

    public function testIsset()
    {
        $this->client->expects($this->once())->method('__call')->with('llen', array('test_key'));
        isset($this->type[15]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIssetAlpha()
    {
        $this->client->expects($this->never())->method('__call');
        isset($this->type['a']);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnset()
    {
        $this->client->expects($this->never())->method('__call');
        unset($this->type[15]);
    }

    public function testPushArrayAccess()
    {
        $this->client->expects($this->once())->method('__call')->with('rpush', array('test_key', 'value'));
        $this->type[] = 'value';
    }

    public function testPush()
    {
        $this->client->expects($this->once())->method('__call')->with('rpush', array('test_key', 'value'));
        $this->type->push('value');
    }

    public function testTryPush()
    {
        $this->client->expects($this->once())->method('__call')->with('rpushx', array('test_key', 'value'));
        $this->type->push('value', true);
    }

    public function testPop()
    {
        $this->client->expects($this->once())->method('__call')->with('rpop', array('test_key'));
        $this->type->pop();
    }

    public function testUnshift()
    {
        $this->client->expects($this->once())->method('__call')->with('lpush', array('test_key', 'value'));
        $this->type->unshift('value');
    }

    public function testTryUnshift()
    {
        $this->client->expects($this->once())->method('__call')->with('lpushx', array('test_key', 'value'));
        $this->type->unshift('value', true);
    }

    public function testShift()
    {
        $this->client->expects($this->once())->method('__call')->with('lpop', array('test_key'));
        $this->type->shift();
    }

    public function testInsert()
    {
        $this->client->expects($this->at(0))->method('__call')->with('linsert', array('test_key', 'before', 'dest', 'value'));
        $this->client->expects($this->at(1))->method('__call')->with('linsert', array('test_key', 'after', 'dest', 'value'));
        $this->type->insert('value', PList::BEFORE, 'dest');
        $this->type->insert('value', PList::AFTER, 'dest');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInsertInvalidArgument()
    {
        $this->type->insert('value', 'something', 'dest');
    }

    public function testCount()
    {
        $this->client->expects($this->once())->method('__call')->with('llen', array('test_key'));
        count($this->type);
    }

    public function testRange()
    {
        $this->client->expects($this->once())->method('__call')->with('lrange', array('test_key', 4, 6));
        $this->type->range(4, 6);
    }

    public function testAll()
    {
        $this->client->expects($this->once())->method('__call')->with('lrange', array('test_key', 0, -1))->will($this->returnValue(array()));
        iterator_to_array($this->type);
    }

    public function testRemove()
    {
        $this->client->expects($this->at(0))->method('__call')->with('lrem', array('test_key', 0, 'value'));
        $this->client->expects($this->at(1))->method('__call')->with('lrem', array('test_key', -3, 'value'));
        $this->client->expects($this->at(2))->method('__call')->with('lrem', array('test_key', 3, 'value'));
        $this->type->remove('value');
        $this->type->remove('value', 3, PList::TAIL_TO_HEAD);
        $this->type->remove('value', 3);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveInvalidArgument()
    {
        $this->type->remove('value', 3, 'something');
    }

    public function testTrim()
    {
        $this->client->expects($this->once())->method('__call')->with('ltrim', array('test_key', 2, 8));
        $this->type->trim(2, 8);
    }

    public function testPopAndPushInto()
    {
        $destList = $this->getMock('Burgov\PredisWrapper\Type\PList', array(), array(), '', false);
        $destList->expects($this->any())->method('getKey')->will($this->returnValue('dest_key'));
        $this->client->expects($this->once())->method('__call')->with('rpoplpush', array('test_key', 'dest_key'));
        $this->type->popAndPushInto($destList);
    }

    public function testBlockPop()
    {
        $this->client->expects($this->exactly(2))->method('__call')->with('brpop', array('test_key', 5));
        $this->type->pop(PList::BLOCK, 5);
        $this->type->blockPop(5);
    }

    public function testBlockShift()
    {
        $this->client->expects($this->exactly(2))->method('__call')->with('blpop', array('test_key', 5));
        $this->type->shift(PList::BLOCK, 5);
        $this->type->blockShift(5);
    }

    public function testBlockPopAndPushInto()
    {
        $destList = $this->getMock('Burgov\PredisWrapper\Type\PList', array(), array(), '', false);
        $destList->expects($this->any())->method('getKey')->will($this->returnValue('dest_key'));
        $this->client->expects($this->exactly(2))->method('__call')->with('brpoplpush', array('test_key', 'dest_key', 5));
        $this->type->popAndPushInto($destList, PList::BLOCK, 5);
        $this->type->blockPopAndPushInto($destList, 5);
    }

    public function testBlockPopMulti()
    {
        $destList = $this->getMock('Burgov\PredisWrapper\Type\PList', array(), array(), '', false);
        $destList->expects($this->any())->method('getKey')->will($this->returnValue('dest_key'));
        $this->client->expects($this->exactly(1))->method('__call')->with('brpop', array('test_key', 'dest_key', 5))->will($this->returnValue(array('key', 'value')));

        PList::blockPopMulti(array($this->type, $destList), 5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBlockPopNone()
    {
        PList::blockPopMulti(array(), 5);
    }

    public function testBlockShiftMulti()
    {
        $destList = $this->getMock('Burgov\PredisWrapper\Type\PList', array(), array(), '', false);
        $destList->expects($this->any())->method('getKey')->will($this->returnValue('dest_key'));
        $this->client->expects($this->exactly(1))->method('__call')->with('blpop', array('test_key', 'dest_key', 5))->will($this->returnValue(array('key', 'value')));

        PList::blockShiftMulti(array($this->type, $destList), 5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBlockShiftNone()
    {
        PList::blockShiftMulti(array(), 5);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateBlockInvalidArgument()
    {
        $this->type->pop('random');
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testValidateBlockBadMethodCall()
    {
        $this->type->pop(PList::NON_BLOCK, 3);
    }
}