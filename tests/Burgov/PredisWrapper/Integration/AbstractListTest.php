<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Type\Set;
use Burgov\PredisWrapper\Type\SortCriteria;

class AbstractListTest extends AbstractIntegrationTest
{
    private $keysSet;

    protected function setUpDatabase()
    {
        $this->keysSet = new Set($this->client, 'keys');

        $this->keysSet->add(3);
        $this->client->set('weight_3', 5);
        $this->client->set('alpha_weight_3', 'e');
        $this->client->hset('keyed_weight_3', 'key', 5);
        $this->client->set('result_3', 'Result 3');
        $this->client->hset('object_3', 'name', 'Object result 3');

        $this->keysSet->add(5);
        $this->client->set('weight_5', 2);
        $this->client->set('alpha_weight_3', 'b');
        $this->client->hset('keyed_weight_5', 'key', 2);
        $this->client->set('result_5', 'Result 5');
        $this->client->hset('object_5', 'name', 'Object result 5');

        $this->keysSet->add(9);
        $this->client->set('weight_9', 8);
        $this->client->set('alpha_weight_3', 'h');
        $this->client->hset('keyed_weight_9', 'key', 9);
        $this->client->set('result_9', 'Result 9');
        $this->client->hset('object_9', 'name', 'Object result 9');
    }


    public function testIntegration()
    {
        $this->assertEquals(array(3, 5, 9), $this->keysSet->sort());

        $sort = new SortCriteria('weight_*');
        $this->assertEquals(array(5, 3, 9), $this->keysSet->sort($sort));

        $sort = new SortCriteria(null, array(2, 2));
        $this->assertEquals(array(9), $this->keysSet->sort($sort));

        $sort = new SortCriteria(null, null, 'result_*');
        $this->assertEquals(array('Result 3', 'Result 5', 'Result 9'), $this->keysSet->sort($sort));

        $sort = new SortCriteria('weight_*', null, 'result_*');
        $this->assertEquals(array('Result 5', 'Result 3', 'Result 9'), $this->keysSet->sort($sort));

        $sort = new SortCriteria('weight_*', null, 'object_*->name');
        $this->assertEquals(
            array('Object result 5', 'Object result 3', 'Object result 9'),
            $this->keysSet->sort($sort)
        );

        $sort = new SortCriteria('keyed_weight_*->key', null, 'object_*->name');
        $this->assertEquals(
            array('Object result 5', 'Object result 3', 'Object result 9'),
            $this->keysSet->sort($sort)
        );

        $sort = new SortCriteria(
            'keyed_weight_*->key',
            null,
            array(SortCriteria::GET_SELF, 'keyed_weight_*->key', 'weight_*', 'result_*', 'object_*->name')
        );
        $this->assertEquals(array(
            array(
                SortCriteria::GET_SELF => 5,
                'keyed_weight_*->key' => 2,
                'weight_*' => 2,
                'result_*' => 'Result 5',
                'object_*->name' => 'Object result 5'
            ),
            array(
                SortCriteria::GET_SELF => 3,
                'keyed_weight_*->key' => 5,
                'weight_*' => 5,
                'result_*' => 'Result 3',
                'object_*->name' => 'Object result 3'
            ),
            array(
                SortCriteria::GET_SELF => 9,
                'keyed_weight_*->key' => 9,
                'weight_*' => 8,
                'result_*' => 'Result 9',
                'object_*->name' => 'Object result 9'
            )
        ), $this->keysSet->sort($sort));
    }
}
