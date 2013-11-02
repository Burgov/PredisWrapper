<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Exception\HashKeyAlreadySetException;
use Burgov\PredisWrapper\TypeFactory;
use Predis\ServerException;

class HashTest extends AbstractIntegrationTest
{
    protected function setUpDatabase()
    {
    }

    public function testIntegration()
    {
        $factory = new TypeFactory($this->client);
        $hash = $factory->instantiate('hash', 'hash');

        $hash['key1'] = 'value1';

        $this->assertTrue(isset($hash['key1']));
        $this->assertEquals('value1', $hash['key1']);

        unset($hash['key1']);

        $this->assertFalse(isset($hash['key1']));
        $this->assertNull($hash['key1']);

        $hash->setKeyValues(array('key1' => 'value1', 'key2' => 'value2'));
        $this->assertEquals(array('key1' => 'value1', 'key2' => 'value2'), iterator_to_array($hash));

        $this->assertCount(2, $hash);

        $hash->trySet('key3', 'value3');
        try {
            $hash->trySet('key3', 'value3');
            $this->fail();
        } catch (HashKeyAlreadySetException $e) {

        }

        $this->assertEquals(array('key1' => 'value1', 'key3' => 'value3'), $hash->getKeyValues(array('key1', 'key3')));

        $this->assertEquals(array('key1', 'key2', 'key3'), $hash->keys());
        $this->assertEquals(array('value1', 'value2', 'value3'), $hash->values());

        $hash['int'] = 5;
        $hash['float'] = 5.5;
        $hash['string'] = 'string';

        $this->assertSame(8, $hash->increment('int', 3));
        $this->assertSame(7.75, $hash->increment('float', 2.25));

        try {
            $hash->increment('string', 3);
        } catch (ServerException $e) {

        }

    }
}
