<?php
/**
 * Created by PhpStorm.
 * User: Bart
 * Date: 26-10-13
 * Time: 16:38
 */

namespace Burgov\PredisWrapper;

use Predis\Client as BaseClient;

class Client extends BaseClient
{
    public function exists($key)
    {
        return (Boolean) parent::exists($key);
    }
    public function delete($key)
    {
        return (Boolean) parent::del($key);
    }

    public function getType($key)
    {
        return parent::type($key);
    }

    public function flushDatabase()
    {
        return parent::flushdb();
    }

    public function find($glob)
    {
        return parent::keys($glob);
    }
} 