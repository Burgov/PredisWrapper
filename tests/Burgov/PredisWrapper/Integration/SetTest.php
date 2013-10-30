<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Type\Set;
use Burgov\PredisWrapper\TypeFactory;

class SetTest extends AbstractIntegrationTest
{
    protected function setUpDatabase()
    {
        $factory = new TypeFactory($this->client);
        foreach (array(
                     '1' => array('a', 'b', 'c'),
                     '2' => array('d', 'e', 'f'),
                     '3' => array()
                 ) as $key => $values) {
            $this->{'set' . $key} = $factory->instantiate('key'.$key, 'set');
            foreach ($values as $value) {
                $this->{'set' . $key}->add($value);
            }
        }
    }


    public function testIntegration()
    {
        $this->assertArrayEquals(array('a', 'b', 'c'), $this->set1->all());

        $this->assertTrue($this->set1->removeElement('b'));
        $this->assertFalse($this->set1->removeElement('d'));

        $this->assertTrue($this->set1->add('d'));
        $this->assertFalse($this->set1->add('c'));

        $this->assertEquals(3, $this->set1->count());

        $this->assertArrayEquals(array('a', 'c'), $this->set1->diff($this->set2));
        $this->assertArrayEquals(array('d'), $this->set1->intersect($this->set2));
        $this->assertArrayEquals(array('a', 'c', 'd', 'e', 'f'), $this->set1->union($this->set2));

        $this->assertArrayEquals(array('a', 'c'), Set::createFromDiff($this->set3, $this->set1, $this->set2)->all());
        $this->assertArrayEquals(array('d'), Set::createFromIntersect($this->set3, $this->set1, $this->set2)->all());
        $this->assertArrayEquals(array('a', 'c', 'd', 'e', 'f'), Set::createFromUnion($this->set3, $this->set1, $this->set2)->all());

        $this->assertTrue($this->set1->contains('a'));
        $this->assertFalse($this->set1->contains('b'));

        $this->assertTrue($this->set1->move($this->set2, 'a'));
        $this->assertArrayEquals(array('c', 'd'), $this->set1->all());
        $this->assertArrayEquals(array('d', 'e', 'f', 'a'), $this->set2->all());

        $this->assertFalse($this->set1->move($this->set2, 'x'));

        $this->assertTrue($this->set3->pop());
        $this->assertEquals(4, $this->set3->count());

        $this->assertTrue(in_array($this->set1->rand(), $this->set1->all()));

        $randUniqueList = $this->set1->randUniqueList(3);
        $this->assertCount(2, $randUniqueList);

        $randList = $this->set1->randList(3);
        $this->assertCount(3, $randList);

    }
}