<?php

namespace Burgov\PredisWrapper\Integration;


use Burgov\PredisWrapper\Type\Scalar;
use Burgov\PredisWrapper\TypeFactory;

class ScalarTest extends AbstractIntegrationTest
{
    public function setUpDatabase()
    {

    }

    /**
     * @medium
     */
    public function testIntegration()
    {
        $factory = new TypeFactory($this->client);
        $string1 = $factory->instantiate('string1', 'string');

        $string1->set("hello");
        $this->assertEquals("hello", (string) $string1);
        $this->assertEquals(5, $string1->getLength());

        $string1->append(" world");
        $this->assertEquals("hello world", (string) $string1);

        $this->assertEquals('lo wor', $string1->getRange(3, 8));

        $string1->setRange(4, " im o");
        $this->assertEquals("hell im old", (string) $string1);

        $string1->set('value', 1);
        usleep(950 * 1000);
        $this->assertEquals('value', $string1->get());
        usleep(100 * 1000);
        $this->assertSame(null, $string1->get());

        $string1->set('value', 1.5);
        usleep(1450 * 1000);
        $this->assertEquals('value', $string1->get());
        usleep(100 * 1000);
        $this->assertSame(null, $string1->get());

        $string1->set('value');
        $this->assertFalse($string1->set('other value', null, Scalar::NO_OVERWRITE));
        $this->assertEquals('value', (string) $string1);

        $string2 = new Scalar($this->client, 'string2');
        $this->assertTrue($string2->set('other value', null, Scalar::NO_OVERWRITE));
        $this->assertEquals('other value', (string) $string2);

        $string3 = new Scalar($this->client, 'string3');
        $this->assertFalse($string3->set('other value', null, Scalar::ONLY_OVERWRITE));
        $this->assertNull($string3->get());
        $string3->set('value');
        $this->assertTrue($string3->set('other value', null, Scalar::ONLY_OVERWRITE));
        $this->assertEquals('other value', (string) $string3);
    }
}
