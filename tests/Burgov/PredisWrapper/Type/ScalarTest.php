<?php

namespace Burgov\PredisWrapper\Type;


class ScalarTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $type;
    public function setUp()
    {
        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')->disableOriginalConstructor()->getMock();

        $this->type = $this->getMockBuilder('Burgov\PredisWrapper\Type\Scalar')
            ->setConstructorArgs(array($this->client, 'test_key'))
            ->setMethods(null)
            ->getMock();
    }

    public function testSet()
    {
        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value'));
        $res = $this->type->set('value');

        $this->assertInternalType('boolean', $res);
    }

    public function testSetTTLSeconds()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.12'));

        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value', 'EX', 5));
        $res = $this->type->set('value', 5);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetTTLSecondsOld()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.11'));

        $this->client->expects($this->once())->method('__call')->with('setex', array('test_key', 5, 'value'));
        $res = $this->type->set('value', 5);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetTTLMilliseconds()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.12'));

        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value', 'PX', 5500));
        $res = $this->type->set('value', 5.5);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetTTLMillisecondsOld()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.11'));

        $this->client->expects($this->once())->method('__call')->with('psetex', array('test_key', 5500, 'value'));
        $res = $this->type->set('value', 5.5);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetNoOverwrite()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.12'));

        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value', 'NX'));
        $res = $this->type->set('value', null, Scalar::NO_OVERWRITE);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetNoOverwriteOldOK()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.11'));

        $this->client->expects($this->exactly(1))->method('exists')->will($this->returnValue(false));
        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value'));
        $res = $this->type->set('value', null, Scalar::NO_OVERWRITE);

        $this->assertInternalType('boolean', $res);
    }


    public function testSetNoOverwriteOldNOK()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.11'));

        $this->client->expects($this->exactly(1))->method('exists')->will($this->returnValue(true));
        $res = $this->type->set('value', null, Scalar::NO_OVERWRITE);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetOnlyOverwrite()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.12'));

        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value', 'XX'));
        $res = $this->type->set('value', null, Scalar::ONLY_OVERWRITE);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetOnlyOverwriteOldOK()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.11'));

        $this->client->expects($this->once())->method('exists')->will($this->returnValue(true));
        $this->client->expects($this->once())->method('__call')->with('set', array('test_key', 'value'));
        $res = $this->type->set('value', null, Scalar::ONLY_OVERWRITE);

        $this->assertInternalType('boolean', $res);
    }

    public function testSetOnlyOverwriteOldNOK()
    {
        $this->client->expects($this->once())->method('getVersion')->will($this->returnValue('2.6.11'));

        $this->client->expects($this->once())->method('exists')->will($this->returnValue(false));
        $res = $this->type->set('value', null, Scalar::ONLY_OVERWRITE);

        $this->assertInternalType('boolean', $res);
    }

    public function testGet()
    {
        $this->client->expects($this->once())->method('__call')->with('get', array('test_key'));
        $this->type->get();
    }

    public function testToString()
    {
        $this->client->expects($this->once())->method('__call')->with('get', array('test_key'));
        (string) $this->type;
    }

    public function testAppend()
    {
        $this->client->expects($this->once())->method('__call')->with('append', array('test_key', 'value'));
        $res = $this->type->append('value');

        $this->assertInternalType('boolean', $res);
    }

    public function testGetRange()
    {
        $this->client->expects($this->once())->method('__call')->with('getrange', array('test_key', 3, 5));
        $this->type->getRange(3, 5);
    }

    public function testSetRange()
    {
        $this->client->expects($this->once())->method('__call')->with('setrange', array('test_key', 3, "something"));
        $this->type->setRange(3, "something");
    }

    public function testGetSet()
    {
        $this->client->expects($this->once())->method('__call')->with('getset', array('test_key', 'test'));
        $this->type->getset('test');
    }

    public function testGetLength()
    {
        $this->client->expects($this->once())->method('__call')->with('strlen', array('test_key'));
        $this->type->getLength();
    }
}
