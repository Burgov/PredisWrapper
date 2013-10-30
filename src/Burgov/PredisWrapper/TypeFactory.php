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

    public function instantiate($key, $default = null)
    {
        $type = $this->client->getType($key);

        if (null !== $default && $type == 'none') {
            $type = $default;
        }

        switch($type) {
            case 'none':
                throw new Exception\KeyDoesNotExistException($key);
            case 'set':
                return new Type\Set($this->client, $key);
            case 'hash':
                return new Type\Hash($this->client, $key);
            case 'list':
                return new Type\PList($this->client, $key);
            case 'string':
                return new Type\String($this->client, $key);
            default:
                throw new Exception\UnknownTypeException($key, $type);
        }
    }

    public function instantiateSet($key)
    {
        return $this->instantiate($key, 'set');
    }

    public function instantiateHash($key)
    {
        return $this->instantiate($key, 'hash');
    }

    public function instantiateScalar($key)
    {
        return $this->instantiate($key, 'string');
    }

    public function instantiateList($key)
    {
        return $this->instantiate($key, 'list');
    }
}