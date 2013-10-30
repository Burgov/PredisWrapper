<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Client;
use Predis\Client as BaseClient;

abstract class AbstractIntegrationTest extends \PHPUnit_Framework_TestCase
{

    protected $version;

    public static function assertArrayEquals($expected, $actual, $message = '')
    {
        try {
            self::assertEmpty(array_diff($expected, $actual), $message);
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            throw new \PHPUnit_Framework_ExpectationFailedException('Failed asserting that array '.json_encode($actual).' equals array ' . json_encode($expected), $e->getComparisonFailure(), $e);
        }
    }

    public function setUp()
    {

        $parameters = array(
            'host' => REDIS_SERVER_HOST,
            'port' => REDIS_SERVER_PORT,
        );

        $options = array();

        $baseClient = new BaseClient($parameters, $options);

        $this->client = new Client($baseClient);
        $baseClient->connect();
        $baseClient->select(REDIS_SERVER_DBNUM);

        $info = $this->client->info('server');
        $this->version = $info['Server']['redis_version'];

        $this->client->flushDatabase();
        $this->setUpDatabase();
    }

    abstract protected function setUpDatabase();
} 