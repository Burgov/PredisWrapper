<?php

namespace Burgov\PredisWrapper\Type;


class SetTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $type;
    public function setUp()
    {
        $this->client = $this->getMockBuilder('Predis\Client')->disableOriginalConstructor()->getMock();

        $this->type = $this->getMockBuilder('Burgov\PredisWrapper\Type\Set')
            ->setConstructorArgs(array($this->client, 'test_key'))
            ->setMethods(null)
            ->getMock();
    }

    public function testAdd()
    {
        $this->client->expects($this->once())->method('__call')->with('sadd', array('test_key', 'test'));
        $res = $this->type->add('test');

        $this->assertInternalType('boolean', $res);
    }

    public function testRemove()
    {
        $this->client->expects($this->once())->method('__call')->with('sremove', array('test_key', 'test'));
        $res = $this->type->remove('test');

        $this->assertInternalType('boolean', $res);
    }

    public function testCount()
    {
        $this->client->expects($this->once())->method('__call')->with('scard', array('test_key'));
        $this->type->count();
    }

    public function testDiff()
    {
        $this->client->expects($this->once())->method('__call')->with('sdiff', array('test_key', 'key2'));
        $this->type->diff(new Set($this->client, 'key2'));
    }

    public function testDiffMultiArgs()
    {
        $this->client->expects($this->once())->method('__call')->with('sdiff', array('test_key', 'key2', 'key3'));
        $this->type->diff(new Set($this->client, 'key2'), new Set($this->client, 'key3'));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testDiffNoArgs()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type->diff();
    }

    public function testIntersect()
    {
        $this->client->expects($this->once())->method('__call')->with('sinter', array('test_key', 'key2'));
        $this->type->intersect(new Set($this->client, 'key2'));
    }

    public function testIntersectMultiArgs()
    {
        $this->client->expects($this->once())->method('__call')->with('sinter', array('test_key', 'key2', 'key3'));
        $this->type->intersect(new Set($this->client, 'key2'), new Set($this->client, 'key3'));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testIntersectNoArgs()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type->intersect();
    }

    public function testContains()
    {
        $this->client->expects($this->once())->method('__call')->with('sismember', array('test_key', 'value'));
        $res = $this->type->contains('value');

        $this->assertInternalType('boolean', $res);
    }

    public function testAll()
    {
        $this->client->expects($this->once())->method('__call')->with('smembers', array('test_key'));
        $this->type->all();
    }

    public function testMove()
    {
        $set2 = new Set($this->client, 'set2');

        $this->client->expects($this->once())->method('__call')->with('smove', array('test_key', 'set2', 'value'));
        $res = $this->type->move($set2, 'value');

        $this->assertInternalType('boolean', $res);
    }

    public function testPop()
    {
        $this->client->expects($this->once())->method('__call')->with('spop', array('test_key'));
        $res = $this->type->pop();

        $this->assertInternalType('boolean', $res);
    }

    public function testRand()
    {
        $this->client->expects($this->once())->method('__call')->with('srandmember', array('test_key'));
        $this->type->rand();
    }

    public function testRandUniqueList()
    {
        $this->client->expects($this->once())->method('__call')->with('srandmember', array('test_key', 2));
        $this->type->randUniqueList(2);
    }

    public function testRandList()
    {
        $this->client->expects($this->once())->method('__call')->with('srandmember', array('test_key', -3));
        $this->type->randList(3);
    }

    public function testRemoveElement()
    {
        $this->client->expects($this->once())->method('__call')->with('srem', array('test_key', 'test'));
        $res = $this->type->removeElement('test');

        $this->assertInternalType('boolean', $res);
    }

    public function testUnion()
    {
        $this->client->expects($this->once())->method('__call')->with('sunion', array('test_key', 'key2'));
        $this->type->union(new Set($this->client, 'key2'));
    }

    public function testUnionMultiArgs()
    {
        $this->client->expects($this->once())->method('__call')->with('sunion', array('test_key', 'key2', 'key3'));
        $this->type->union(new Set($this->client, 'key2'), new Set($this->client, 'key3'));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnionNoArgs()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type->union();
    }

    public function testDiffStore()
    {
        $this->client->expects($this->once())->method('__call')->with('sdiffstore', array('key3', 'key1', 'key2'));
        $res = Set::createFromDiff('key3', new Set($this->client, 'key1'), new Set($this->client, 'key2'));

        $this->assertInstanceOf('Burgov\PredisWrapper\Type\Set', $res);
        $this->assertEquals('key3', $res->getKey());
    }

    public function testDiffStoreMultiArgs()
    {
        $this->client->expects($this->once())->method('__call')->with('sdiffstore', array('key3', 'key1', 'key2', 'key4'));
        $res = Set::createFromDiff('key3', new Set($this->client, 'key1'), new Set($this->client, 'key2'), new Set($this->client, 'key4'));

        $this->assertInstanceOf('Burgov\PredisWrapper\Type\Set', $res);
        $this->assertEquals('key3', $res->getKey());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testDiffStoreNoArgs()
    {
        $this->client->expects($this->never())->method('__call');
        $res = Set::createFromDiff('key3', new Set($this->client, 'key1'));
    }

    public function testIntersectStore()
    {
        $this->client->expects($this->once())->method('__call')->with('sinterstore', array('key3', 'key1', 'key2'));
        $res = Set::createFromIntersect('key3', new Set($this->client, 'key1'), new Set($this->client, 'key2'));

        $this->assertInstanceOf('Burgov\PredisWrapper\Type\Set', $res);
        $this->assertEquals('key3', $res->getKey());
    }

    public function testIntersectStoreMultiArgs()
    {
        $this->client->expects($this->once())->method('__call')->with('sinterstore', array('key3', 'key1', 'key2', 'key4'));
        $res = Set::createFromIntersect('key3', new Set($this->client, 'key1'), new Set($this->client, 'key2'), new Set($this->client, 'key4'));

        $this->assertInstanceOf('Burgov\PredisWrapper\Type\Set', $res);
        $this->assertEquals('key3', $res->getKey());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testIntersectStoreNoArgs()
    {
        $this->client->expects($this->never())->method('__call');
        $res = Set::createFromIntersect('key3', new Set($this->client, 'key1'));
    }

    public function testUnionStore()
    {
        $this->client->expects($this->once())->method('__call')->with('sunionstore', array('key3', 'key1', 'key2'));
        $res = Set::createFromUnion('key3', new Set($this->client, 'key1'), new Set($this->client, 'key2'));

        $this->assertInstanceOf('Burgov\PredisWrapper\Type\Set', $res);
        $this->assertEquals('key3', $res->getKey());
    }

    public function testUnionStoreMultiArgs()
    {
        $this->client->expects($this->once())->method('__call')->with('sunionstore', array('key3', 'key1', 'key2', 'key4'));
        $res = Set::createFromUnion('key3', new Set($this->client, 'key1'), new Set($this->client, 'key2'), new Set($this->client, 'key4'));

        $this->assertInstanceOf('Burgov\PredisWrapper\Type\Set', $res);
        $this->assertEquals('key3', $res->getKey());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testUnionStoreNoArgs()
    {
        $this->client->expects($this->never())->method('__call');
        $res = Set::createFromUnion('key3', new Set($this->client, 'key1'));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testMultiInvalid()
    {
        $this->type->union(new \DateTime());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testMultiStoreInvalid()
    {
        $res = Set::createFromUnion('key3', new \DateTime());
    }
}