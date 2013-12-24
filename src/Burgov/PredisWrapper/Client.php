<?php

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

    /**
     * @return string The server version
     */
    public function getVersion()
    {
        if (null === $this->version) {
            $info = $this->client->info();

            // it appears that on some machines, the previous line will return a deep array, and on some a flat array
            $this->version = array_key_exists('Server', $info)
                ? $info['Server']['redis_version']
                : $info['redis_version'];
        }

        return $this->version;
    }

    /**
     * Check if the specified key exists
     * Wraps command EXISTS
     *
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return (Boolean) $this->client->exists(AbstractType::key($key));
    }

    /**
     * Delete the specified key
     * Wraps command DEL
     *
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        return (Boolean) $this->client->del(AbstractType::key($key));
    }

    /**
     * Key the type of the specified key
     * Wraps command TYPE
     *
     * @param $key
     * @return mixed
     */
    public function getType($key)
    {
        return $this->client->type($key);
    }

    /**
     * Flush the whole database
     * Wraps command FLUSHDB
     *
     * @return string
     */
    public function flushDatabase()
    {
        return $this->client->flushdb();
    }

    /**
     * Finds keys matched by a glod
     * Wraps command KEYS
     *
     * @param $glob
     * @return array
     */
    public function find($glob)
    {
        return $this->client->keys($glob);
    }
}
