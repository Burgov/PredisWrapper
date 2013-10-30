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

class Client extends BaseClient
{
    public function exists($key)
    {
        return (Boolean) parent::exists(AbstractType::key($key));
    }
    public function delete($key)
    {
        return (Boolean) parent::del(AbstractType::key($key));
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