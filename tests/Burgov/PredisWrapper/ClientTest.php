<?php

namespace Burgov\PredisWrapper;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    private $baseClient;

    private $client;

    public function setUp()
    {
        $this->baseClient = $this->getMockBuilder('Predis\Client')
            ->disableOriginalConstructor()->setMethods(array('__call'))->getMock();

        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')
            ->setConstructorArgs(array($this->baseClient))
            ->setMethods(null)
            ->getMock();
    }

    public function testExists()
    {
        $this->baseClient->expects($this->once())->method('__call')->with('exists', array('key1'));
        $this->client->exists('key1');
    }

    public function testDelete()
    {
        $this->baseClient->expects($this->once())->method('__call')->with('del', array('key1'));
        $this->client->delete('key1');
    }

    public function testFind()
    {
        $this->baseClient->expects($this->once())->method('__call')->with('keys', array('some?search'));
        $this->client->find('some?search');
    }

    public function testExpire()
    {
        $this->baseClient->expects($this->once())->method('__call')->with('expire', array('key', 5));
        $this->client->expire('key', 5);
    }

    public function testExpireWithFloat()
    {
        $this->baseClient->expects($this->once())->method('__call')->with('pexpire', array('key', 5500));
        $this->client->expire('key', 5.5);
    }
}
