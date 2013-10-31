<?php

namespace Burgov\PredisWrapper;

class TypeFactory
{
    private $instances = array();

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function instantiate($key, $default = null)
    {
        if (array_key_exists($key, $this->instances)) {
            return $this->instances[$key];
        }

        $type = $this->client->getType($key);

        if (null !== $default && $type == 'none') {
            $type = $default;
        }

        switch($type) {
            case 'none':
                throw new Exception\KeyDoesNotExistException($key);
            case 'set':
                $object = new Type\Set($this->client, $key);
                break;
            case 'hash':
                $object = new Type\Hash($this->client, $key);
                break;
            case 'list':
                $object = new Type\PList($this->client, $key);
                break;
            case 'string':
                $object = new Type\Scalar($this->client, $key);
                break;
            case 'sortedset':
                $object = new Type\SortedSet($this->client, $key);
                break;
            default:
                throw new Exception\UnknownTypeException($key, $type);
        }

        return $this->instances[$key] = $object;
    }

    public function instantiateSet($key)
    {
        $object = $this->instantiate($key, 'set');
        if (!$object instanceof Type\Set) {
            throw new Exception\WrongTypeException('set', get_class($object));
        }
        return $object;
    }

    public function instantiateHash($key)
    {
        $object = $this->instantiate($key, 'hash');
        if (!$object instanceof Type\Hash) {
            throw new Exception\WrongTypeException('hash', get_class($object));
        }
        return $object;
    }

    public function instantiateScalar($key)
    {
        $object = $this->instantiate($key, 'string');
        if (!$object instanceof Type\Scalar) {
            throw new Exception\WrongTypeException('string', get_class($object));
        }
        return $object;
    }

    public function instantiateList($key)
    {
        $object = $this->instantiate($key, 'list');
        if (!$object instanceof Type\PList) {
            throw new Exception\WrongTypeException('list', get_class($object));
        }
        return $object;
    }

    public function instantiateSortedSet($key)
    {
        $object = $this->instantiate($key, 'sortedset');
        if (!$object instanceof Type\SortedSet) {
            throw new Exception\WrongTypeException('sortedset', get_class($object));
        }
        return $object;
    }
}