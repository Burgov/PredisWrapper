<?php

namespace Burgov\PredisWrapper\Type;


use Burgov\PredisWrapper\Client;

class AbstractType
{
    private $key;
    private $client;

    public function __construct(Client $client, $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    /**
     * Get the key name of the specified key.
     * For convenience, this method will simply return the passed argument if it is not an instance of self
     * Useful for when you don't know if the key is the name of the key or an instance of a Type
     *
     * @param $key
     * @return string
     */
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

    /**
     * Get the name of the Type
     *
     * @return string
     */
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