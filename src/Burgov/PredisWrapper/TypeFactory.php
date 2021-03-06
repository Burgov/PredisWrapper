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

    /**
     * @param string $key
     * @param string $default if the type does not exist, what should be make of it? null if it should result in error
     * @return Type\Hash|Type\PList|Type\Scalar|Type\Set|Type\AbstractType
     * @throws Exception\UnknownTypeException
     * @throws Exception\KeyDoesNotExistException
     */
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

    /**
     * @param $key
     * @return Type\Hash|Type\PList|Type\Scalar|Type\Set|Type\SortedSet
     * @throws Exception\WrongTypeException
     */
    public function instantiateSet($key)
    {
        $object = $this->instantiate($key, 'set');
        if (!$object instanceof Type\Set) {
            throw new Exception\WrongTypeException('set', get_class($object));
        }
        return $object;
    }

    /**
     * @param $key
     * @return Type\Hash|Type\PList|Type\Scalar|Type\Set|Type\SortedSet
     * @throws Exception\WrongTypeException
     */
    public function instantiateHash($key)
    {
        $object = $this->instantiate($key, 'hash');
        if (!$object instanceof Type\Hash) {
            throw new Exception\WrongTypeException('hash', get_class($object));
        }
        return $object;
    }

    /**
     * @param $key
     * @return Type\Hash|Type\PList|Type\Scalar|Type\Set|Type\SortedSet
     * @throws Exception\WrongTypeException
     */
    public function instantiateScalar($key)
    {
        $object = $this->instantiate($key, 'string');
        if (!$object instanceof Type\Scalar) {
            throw new Exception\WrongTypeException('string', get_class($object));
        }
        return $object;
    }

    /**
     * @param $key
     * @return Type\Hash|Type\PList|Type\Scalar|Type\Set|Type\SortedSet
     * @throws Exception\WrongTypeException
     */
    public function instantiateList($key)
    {
        $object = $this->instantiate($key, 'list');
        if (!$object instanceof Type\PList) {
            throw new Exception\WrongTypeException('list', get_class($object));
        }
        return $object;
    }

    /**
     * @param $key
     * @return Type\Hash|Type\PList|Type\Scalar|Type\Set|Type\SortedSet
     * @throws Exception\WrongTypeException
     */
    public function instantiateSortedSet($key)
    {
        $object = $this->instantiate($key, 'sortedset');
        if (!$object instanceof Type\SortedSet) {
            throw new Exception\WrongTypeException('sortedset', get_class($object));
        }
        return $object;
    }
}
