<?php
/**
 * Created by PhpStorm.
 * User: Bart
 * Date: 26-10-13
 * Time: 16:38
 */

namespace Burgov\PredisWrapper;

use Burgov\PredisWrapper\Type\AbstractType;
use Predis\Client as BaseClient;

class Client
{
    private $version;

    private $client;

    public function __construct(BaseClient $client)
    {
        $this->client = $client;
    }

    public function __call($method, array $arguments)
    {
        return call_user_func_array(array($this->client, $method), $arguments);
    }

    public function getVersion()
    {
        if (null === $this->version) {
            $info = $this->client->info('server');
            $this->version = $info['Server']['redis_version'];
        }

        return $this->version;
    }

    public function exists($key)
    {
        return (Boolean) $this->client->exists(AbstractType::key($key));
    }
    public function delete($key)
    {
        return (Boolean) $this->client->del(AbstractType::key($key));
    }

    public function getType($key)
    {
        return $this->client->type($key);
    }

    public function flushDatabase()
    {
        return $this->client->flushdb();
    }

    public function find($glob)
    {
        return $this->client->keys($glob);
    }
} 