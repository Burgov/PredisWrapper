<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Type\Set;

class KeyTest extends AbstractIntegrationTest
{
    private $set1, $set2, $set3;

    protected function setUpDatabase()
    {
        $this->set1 = new Set($this->client, 'set1');
        $this->set1->add('value');
        $this->set2 = new Set($this->client, 'set2');
        $this->set2->add('value');
        $this->set3 = new Set($this->client, 'set3');
        $this->set3->add('value');
    }

    public function testIntegration()
    {
        $this->assertEquals('set', $this->client->getType('set1'));
        $this->assertEquals('none', $this->client->getType('something_random'));

        $this->assertTrue($this->client->exists('set1'));
        $this->assertFalse($this->client->exists('set4'));

        $this->assertTrue($this->client->delete('set1'));
        $this->assertFalse($this->client->exists('set1'));
        $this->assertFalse($this->client->delete('set1'));

        $this->assertArrayEquals(array('set2', 'set3'), $this->client->find('set?'));
    }
} 