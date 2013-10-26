<?php

namespace Burgov\PredisWrapper;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('__call'))
            ->getMock();
    }

    public function testExists()
    {
        $this->client->expects($this->once())->method('__call')->with('exists', array('key1'));
        $this->client->exists('key1');
    }

    public function testDelete()
    {
        $this->client->expects($this->once())->method('__call')->with('del', array('key1'));
        $this->client->delete('key1');
    }

    public function testFind()
    {
        $this->client->expects($this->once())->method('__call')->with('keys', array('some?search'));
        $this->client->find('some?search');
    }
}