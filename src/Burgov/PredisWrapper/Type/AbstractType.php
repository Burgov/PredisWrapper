<?php

namespace Burgov\PredisWrapper\Type;


use Predis\Client;

class AbstractType
{
    private $key;
    private $client;

    public function __construct(Client $client, $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public static function key($key) {
        if ($key instanceof self) {
            return $key->getKey();
        }
        return (string) $key;
    }

    protected function getClient()
    {
        return $this->client;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function execute($command)
    {
        $arguments = array_slice(func_get_args(), 1);
        $arguments = array_map(function($argument) {
            return $argument;
        }, $arguments);
        array_unshift($arguments, $this->key);

        return call_user_func_array(array($this->client, $command), $arguments);
    }
}