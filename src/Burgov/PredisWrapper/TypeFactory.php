<?php

namespace Burgov\PredisWrapper;

use Predis\Command\KeyType;

class TypeFactory
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function instantiate($key)
    {
        $type = $this->client->getType($key);

        switch($type) {
            case 'none':
                throw new Exception\KeyDoesNotExistException($key);
            case 'set':
                return new Type\Set($this->client, $key);
            default:
                throw new Exception\UnknownTypeException($key, $type);
        }
    }

    private function verifyType($key, $type)
    {
        $res = $this->client->getType($key);
        if ($type !== $res) {
            throw new Exception\WrongTypeException($type, $res);
        }
    }

    public function instantiateSet($key)
    {
        $this->verifyType($key, 'set');

        return new Type\Set($this->client, $key);
    }
}