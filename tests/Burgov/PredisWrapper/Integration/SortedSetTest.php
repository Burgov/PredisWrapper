<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Type\Set;
use Burgov\PredisWrapper\Type\SortedSet;
use Burgov\PredisWrapper\Type\SortedValue;
use Burgov\PredisWrapper\TypeFactory;

class SortedSetTest extends AbstractIntegrationTest
{
    private $set1, $set2, $set3;

    protected function setUpDatabase()
    {
        $factory = new TypeFactory($this->client);

        $this->set1 = $factory->instantiateSortedSet('set1');
        $this->set1->add(new SortedValue('a', 1), new SortedValue('b', 2), new SortedValue('c', 3));
        $this->set2 = $factory->instantiateSortedSet('set2');
        $this->set2->add(new SortedValue('a', 3), new SortedValue('y', 6), new SortedValue('r', 11), new SortedValue('b', 2), new SortedValue('c', 9));
        $this->set3 = $factory->instantiateSortedSet('set3');
        $this->set3->add(new SortedValue('b', 8), new SortedValue('d', 10));
    }

    public function testIntegration()
    {
        $this->assertCount(3, $this->set1);
        $this->assertEquals(1, $this->set1->count(2, 2));
        $this->assertEquals(1, $this->set1->count("(1", "(3"));

        $this->set1->remove('b');
        $this->assertCount(2, $this->set1);
        $this->assertEquals(3, $this->set2->getRank('c'));
        $this->assertEquals(9, $this->set2->getScore('c'));

        $this->set1->incrementScore('c', 2);
        $this->assertEquals(5, $this->set1->getScore('c'));
    }

    public function testGetRange()
    {
        $this->assertEquals(array('b', 'a', 'y', 'c', 'r'), $this->set2->getRange());
        $this->assertEquals(array('a', 'y', 'c'), $this->set2->getRange(1, -2));

        $this->assertEquals(array('r', 'c', 'y', 'a', 'b'), $this->set2->getRange(0, -1, SortedSet::REVERSE));

        $this->assertEquals(array('y', 'c'), $this->set2->getRange(5, 10, SortedSet::BY_SCORE));
        $this->assertEquals(array('a', 'y'), $this->set2->getRange(3, 9, SortedSet::BY_SCORE, array(0, 2)));

        $this->assertEquals(array(new SortedValue('a', 1), new SortedValue('b', 2), new SortedValue('c', 3)), $this->set1->getRange(0, -1, SortedSet::WITH_SCORES));
    }

    public function testCreateFrom()
    {

        $set4 = SortedSet::createFromUnion('set4', array($this->set1, $this->set2, $this->set3), array(1, 3), SortedSet::AGGREGATE_MAX);
        $this->assertEquals(array('b', 'a', 'd', 'y', 'c', 'r'), iterator_to_array($set4));

        SortedSet::createFromIntersect('set4', array($this->set1, $this->set2, $this->set3), array(1, 3), SortedSet::AGGREGATE_MAX);
        $this->assertEquals(array('b'), iterator_to_array($set4));
    }

    public function testRemoveByRank()
    {
        $this->set2->removeRange(1, 3);
        $this->assertEquals(array('b', 'r'), iterator_to_array($this->set2));
    }

    public function testRemoveByScore()
    {
        $this->set2->removeRange(3, 8, SortedSet::BY_SCORE);
        $this->assertEquals(array('b', 'c', 'r'), iterator_to_array($this->set2));
    }
}