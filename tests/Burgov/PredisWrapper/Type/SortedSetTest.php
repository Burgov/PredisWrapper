<?php

namespace Burgov\PredisWrapper\Type;


class SortedSetTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $type;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')->disableOriginalConstructor()->getMock();

        $this->type = $this->getMockBuilder('Burgov\PredisWrapper\Type\SortedSet')
            ->setConstructorArgs(array($this->client, 'test_key'))
            ->setMethods(null)
            ->getMock();
    }

    public function testAdd()
    {
        $this->client->expects($this->once())->method('__call')->with('zadd', array('test_key', 1, 'one'));
        $this->type->add(new SortedValue('one', 1));
    }

    public function testAddMulti()
    {
        $this->client->expects($this->once())->method('__call')->with('zadd', array('test_key', 1, 'one', 2, 'two'));
        $this->type->add(new SortedValue('one', 1), new SortedValue('two', 2));
    }

    public function testRemove()
    {
        $this->client->expects($this->once())->method('__call')->with('zrem', array('test_key', 'one'));
        $this->type->remove('one');
    }

    public function testRemoveMulti()
    {
        $this->client->expects($this->once())->method('__call')->with('zrem', array('test_key', 'one', 'two'));
        $this->type->remove('one', 'two');
    }

    public function testCount()
    {
        $this->client->expects($this->once())->method('__call')->with('zcard', array('test_key'));
        count($this->type);
    }

    public function testGetRank()
    {
        $this->client->expects($this->once())->method('__call')->with('zrank', array('test_key', 'one'));
        $this->type->getRank('one');
    }

    public function testGetRevRank()
    {
        $this->client->expects($this->once())->method('__call')->with('zrevrank', array('test_key', 'one'));
        $this->type->getRank('one', SortedSet::REVERSE);
    }

    public function testGetScore()
    {
        $this->client->expects($this->once())->method('__call')->with('zscore', array('test_key', 'one'));
        $this->type->getScore('one');
    }

    public function testCountScores()
    {
        $this->client->expects($this->once())->method('__call')->with('zcount', array('test_key', 1, 3));
        $this->type->count(1, 3);
    }

    public function testIncrementScore()
    {
        $this->client->expects($this->once())->method('__call')->with('zincrby', array('test_key', 3, 'value'));
        $this->type->incrementScore('value', 3);
    }

    public function testCreateFromUnion()
    {
        $this->client->expects($this->once())->method('__call')->with('zunionstore', array(
            'key4', 3, 'key1', 'key2', 'key3', 'WEIGHTS', 1, 2, 1, 'AGGREGATE', 'MIN'));
        SortedSet::createFromUnion(
            'key4',
            array(
                new SortedSet($this->client, 'key1'),
                new SortedSet($this->client, 'key2'),
                new SortedSet($this->client, 'key3')
            ),
            array(1, 2),
            SortedSet::AGGREGATE_MIN
        );
    }

    public function testCreateFromIntersect()
    {
        $this->client->expects($this->once())->method('__call')->with('zinterstore', array(
            'key4', 3, 'key1', 'key2', 'key3', 'WEIGHTS', 1, 2, 1, 'AGGREGATE', 'MIN'));
        SortedSet::createFromIntersect(
            'key4',
            array(
                new SortedSet($this->client, 'key1'),
                new SortedSet($this->client, 'key2'),
                new SortedSet($this->client, 'key3')
            ),
            array(1, 2),
            SortedSet::AGGREGATE_MIN
        );
    }

    public function testGetAll()
    {
        $this->client->expects($this->once())->method('__call')
            ->with('zrange', array('test_key', 0, -1))->will($this->returnValue(array()));
        iterator_to_array($this->type);
    }

    public function testGetRange()
    {
        $this->client->expects($this->once())->method('__call')
            ->with('zrange', array('test_key', 5, -3))->will($this->returnValue(array()));
        $this->type->getRange(5, -3);
    }

    public function testGetRevRange()
    {
        $this->client->expects($this->once())->method('__call')
            ->with('zrevrange', array('test_key', 5, -3))->will($this->returnValue(array()));
        $this->type->getRange(5, -3, SortedSet::REVERSE);
    }

    public function testGetRangeByScore()
    {
        $this->client->expects($this->once())->method('__call')
            ->with('zrangebyscore', array('test_key', 5, -3, 'limit', 2, 5))->will($this->returnValue(array()));
        $this->type->getRange(5, -3, SortedSet::BY_SCORE, array(2, 5));
    }

    public function testGetRevRangeByScore()
    {
        $this->client->expects($this->once())->method('__call')
            ->with('zrevrangebyscore', array('test_key', 5, -3, 'limit', 2, 5))->will($this->returnValue(array()));
        $this->type->getRange(5, -3, SortedSet::BY_SCORE | SortedSet::REVERSE, array(2, 5));
    }

    public function testGetRangeByScoreWithScores()
    {
        $this->client->expects($this->once())->method('__call')
            ->with('zrangebyscore', array('test_key', 5, -3, 'withscores'))->will($this->returnValue(array()));
        $this->type->getRange(5, -3, SortedSet::BY_SCORE | SortedSet::WITH_SCORES);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetRangeWithLimit()
    {
        $this->client->expects($this->never())->method('__call');
        $this->type->getRange(5, -3, null, array(1, 3));
    }

    public function testRemRangeByRank()
    {
        $this->client->expects($this->once())->method('__call')->with('zremrangebyrank', array('test_key', 5, -3));
        $this->type->removeRange(5, -3);
    }

    public function testRemRangeByScore()
    {
        $this->client->expects($this->once())->method('__call')->with('zremrangebyscore', array('test_key', 5, -3));
        $this->type->removeRange(5, -3, SortedSet::BY_SCORE);
    }
}
