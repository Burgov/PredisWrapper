[APPEND](http://redis.io/commands/APPEND): wrapped by [Burgov\PredisWrapper\Type\Scalar::append](../src/Burgov/PredisWrapper/Type/Scalar.php#L107)  
[AUTH](http://redis.io/commands/AUTH): not wrapped  
[BGREWRITEAOF](http://redis.io/commands/BGREWRITEAOF): not wrapped  
[BGSAVE](http://redis.io/commands/BGSAVE): not wrapped  
[BITCOUNT](http://redis.io/commands/BITCOUNT): not wrapped  
[BITOP](http://redis.io/commands/BITOP): not wrapped  
[BITPOS](http://redis.io/commands/BITPOS): not wrapped  
[BLPOP](http://redis.io/commands/BLPOP): wrapped by [Burgov\PredisWrapper\Type\PList::blockShiftMulti](../src/Burgov/PredisWrapper/Type/PList.php#L224)  
[BRPOP](http://redis.io/commands/BRPOP): wrapped by [Burgov\PredisWrapper\Type\PList::blockPopMulti](../src/Burgov/PredisWrapper/Type/PList.php#L172)  
[BRPOPLPUSH](http://redis.io/commands/BRPOPLPUSH): wrapped by [Burgov\PredisWrapper\Type\PList::blockPopAndPushInto](../src/Burgov/PredisWrapper/Type/PList.php#L324)  
[CLIENT KILL](http://redis.io/commands/CLIENT KILL): not wrapped  
[CLIENT LIST](http://redis.io/commands/CLIENT LIST): not wrapped  
[CLIENT GETNAME](http://redis.io/commands/CLIENT GETNAME): not wrapped  
[CLIENT PAUSE](http://redis.io/commands/CLIENT PAUSE): not wrapped  
[CLIENT SETNAME](http://redis.io/commands/CLIENT SETNAME): not wrapped  
[CONFIG GET](http://redis.io/commands/CONFIG GET): not wrapped  
[CONFIG REWRITE](http://redis.io/commands/CONFIG REWRITE): not wrapped  
[CONFIG SET](http://redis.io/commands/CONFIG SET): not wrapped  
[CONFIG RESETSTAT](http://redis.io/commands/CONFIG RESETSTAT): not wrapped  
[DBSIZE](http://redis.io/commands/DBSIZE): not wrapped  
[DEBUG OBJECT](http://redis.io/commands/DEBUG OBJECT): not wrapped  
[DEBUG SEGFAULT](http://redis.io/commands/DEBUG SEGFAULT): not wrapped  
[DECR](http://redis.io/commands/DECR): not wrapped  
[DECRBY](http://redis.io/commands/DECRBY): not wrapped  
[DEL](http://redis.io/commands/DEL): wrapped by [Burgov\PredisWrapper\Client::delete](../src/Burgov/PredisWrapper/Client.php#L60)  
[DISCARD](http://redis.io/commands/DISCARD): not wrapped  
[DUMP](http://redis.io/commands/DUMP): not wrapped  
[ECHO](http://redis.io/commands/ECHO): not wrapped  
[EVAL](http://redis.io/commands/EVAL): not wrapped  
[EVALSHA](http://redis.io/commands/EVALSHA): not wrapped  
[EXEC](http://redis.io/commands/EXEC): not wrapped  
[EXISTS](http://redis.io/commands/EXISTS): wrapped by [Burgov\PredisWrapper\Client::exists](../src/Burgov/PredisWrapper/Client.php#L48)  
[EXPIRE](http://redis.io/commands/EXPIRE): wrapped by [Burgov\PredisWrapper\Client::expire](../src/Burgov/PredisWrapper/Client.php#L108)  
[EXPIREAT](http://redis.io/commands/EXPIREAT): not wrapped  
[FLUSHALL](http://redis.io/commands/FLUSHALL): not wrapped  
[FLUSHDB](http://redis.io/commands/FLUSHDB): wrapped by [Burgov\PredisWrapper\Client::flushDatabase](../src/Burgov/PredisWrapper/Client.php#L83)  
[GET](http://redis.io/commands/GET): wrapped by [Burgov\PredisWrapper\Type\Scalar::get](../src/Burgov/PredisWrapper/Type/Scalar.php#L91)  
[GETBIT](http://redis.io/commands/GETBIT): not wrapped  
[GETRANGE](http://redis.io/commands/GETRANGE): wrapped by [Burgov\PredisWrapper\Type\Scalar::getRange](../src/Burgov/PredisWrapper/Type/Scalar.php#L119)  
[GETSET](http://redis.io/commands/GETSET): wrapped by [Burgov\PredisWrapper\Type\Scalar::getset](../src/Burgov/PredisWrapper/Type/Scalar.php#L142)  
[HDEL](http://redis.io/commands/HDEL): wrapped by [Burgov\PredisWrapper\Type\Hash::offsetUnset](../src/Burgov/PredisWrapper/Type/Hash.php#L48)  
[HEXISTS](http://redis.io/commands/HEXISTS): wrapped by [Burgov\PredisWrapper\Type\Hash::offsetExists](../src/Burgov/PredisWrapper/Type/Hash.php#L16)  
[HGET](http://redis.io/commands/HGET): wrapped by [Burgov\PredisWrapper\Type\Hash::offsetGet](../src/Burgov/PredisWrapper/Type/Hash.php#L27)  
[HGETALL](http://redis.io/commands/HGETALL): wrapped by [Burgov\PredisWrapper\Type\Hash::toArray](../src/Burgov/PredisWrapper/Type/Hash.php#L58)  
[HINCRBY](http://redis.io/commands/HINCRBY): wrapped by [Burgov\PredisWrapper\Type\Hash::increment](../src/Burgov/PredisWrapper/Type/Hash.php#L80)  
[HINCRBYFLOAT](http://redis.io/commands/HINCRBYFLOAT): wrapped by [Burgov\PredisWrapper\Type\Hash::increment](../src/Burgov/PredisWrapper/Type/Hash.php#L80)  
[HKEYS](http://redis.io/commands/HKEYS): wrapped by [Burgov\PredisWrapper\Type\Hash::keys](../src/Burgov/PredisWrapper/Type/Hash.php#L107)  
[HLEN](http://redis.io/commands/HLEN): wrapped by [Burgov\PredisWrapper\Type\Hash::count](../src/Burgov/PredisWrapper/Type/Hash.php#L128)  
[HMGET](http://redis.io/commands/HMGET): wrapped by [Burgov\PredisWrapper\Type\Hash::getKeyValues](../src/Burgov/PredisWrapper/Type/Hash.php#L140)  
[HMSET](http://redis.io/commands/HMSET): wrapped by [Burgov\PredisWrapper\Type\Hash::setKeyValues](../src/Burgov/PredisWrapper/Type/Hash.php#L151)  
[HSET](http://redis.io/commands/HSET): wrapped by [Burgov\PredisWrapper\Type\Hash::offsetSet](../src/Burgov/PredisWrapper/Type/Hash.php#L38)  
[HSETNX](http://redis.io/commands/HSETNX): wrapped by [Burgov\PredisWrapper\Type\Hash::trySet](../src/Burgov/PredisWrapper/Type/Hash.php#L164)  
[HVALS](http://redis.io/commands/HVALS): wrapped by [Burgov\PredisWrapper\Type\Hash::values](../src/Burgov/PredisWrapper/Type/Hash.php#L118)  
[INCR](http://redis.io/commands/INCR): not wrapped  
[INCRBY](http://redis.io/commands/INCRBY): not wrapped  
[INCRBYFLOAT](http://redis.io/commands/INCRBYFLOAT): not wrapped  
[INFO](http://redis.io/commands/INFO): not wrapped  
[KEYS](http://redis.io/commands/KEYS): wrapped by [Burgov\PredisWrapper\Client::find](../src/Burgov/PredisWrapper/Client.php#L95)  
[LASTSAVE](http://redis.io/commands/LASTSAVE): not wrapped  
[LINDEX](http://redis.io/commands/LINDEX): wrapped by [Burgov\PredisWrapper\Type\PList::offsetGet](../src/Burgov/PredisWrapper/Type/PList.php#L63)  
[LINSERT](http://redis.io/commands/LINSERT): wrapped by [Burgov\PredisWrapper\Type\PList::insert](../src/Burgov/PredisWrapper/Type/PList.php#L258)  
[LLEN](http://redis.io/commands/LLEN): wrapped by [Burgov\PredisWrapper\Type\PList::count](../src/Burgov/PredisWrapper/Type/PList.php#L104)  
[LPOP](http://redis.io/commands/LPOP): wrapped by [Burgov\PredisWrapper\Type\PList::shift](../src/Burgov/PredisWrapper/Type/PList.php#L205)  
[LPUSH](http://redis.io/commands/LPUSH): wrapped by [Burgov\PredisWrapper\Type\PList::unshift](../src/Burgov/PredisWrapper/Type/PList.php#L142)  
[LPUSHX](http://redis.io/commands/LPUSHX): wrapped by [Burgov\PredisWrapper\Type\PList::unshift](../src/Burgov/PredisWrapper/Type/PList.php#L142)  
[LRANGE](http://redis.io/commands/LRANGE): wrapped by [Burgov\PredisWrapper\Type\PList::range](../src/Burgov/PredisWrapper/Type/PList.php#L22)  
[LREM](http://redis.io/commands/LREM): wrapped by [Burgov\PredisWrapper\Type\PList::remove](../src/Burgov/PredisWrapper/Type/PList.php#L274)  
[LSET](http://redis.io/commands/LSET): wrapped by [Burgov\PredisWrapper\Type\PList::offsetSet](../src/Burgov/PredisWrapper/Type/PList.php#L78)  
[LTRIM](http://redis.io/commands/LTRIM): wrapped by [Burgov\PredisWrapper\Type\PList::trim](../src/Burgov/PredisWrapper/Type/PList.php#L292)  
[MGET](http://redis.io/commands/MGET): not wrapped  
[MIGRATE](http://redis.io/commands/MIGRATE): not wrapped  
[MONITOR](http://redis.io/commands/MONITOR): not wrapped  
[MOVE](http://redis.io/commands/MOVE): not wrapped  
[MSET](http://redis.io/commands/MSET): not wrapped  
[MSETNX](http://redis.io/commands/MSETNX): not wrapped  
[MULTI](http://redis.io/commands/MULTI): not wrapped  
[OBJECT](http://redis.io/commands/OBJECT): not wrapped  
[PERSIST](http://redis.io/commands/PERSIST): not wrapped  
[PEXPIRE](http://redis.io/commands/PEXPIRE): wrapped by [Burgov\PredisWrapper\Client::expire](../src/Burgov/PredisWrapper/Client.php#L108)  
[PEXPIREAT](http://redis.io/commands/PEXPIREAT): not wrapped  
[PING](http://redis.io/commands/PING): not wrapped  
[PSETEX](http://redis.io/commands/PSETEX): wrapped by [Burgov\PredisWrapper\Type\Scalar::set](../src/Burgov/PredisWrapper/Type/Scalar.php#L23)  
[PSUBSCRIBE](http://redis.io/commands/PSUBSCRIBE): not wrapped  
[PUBSUB](http://redis.io/commands/PUBSUB): not wrapped  
[PTTL](http://redis.io/commands/PTTL): not wrapped  
[PUBLISH](http://redis.io/commands/PUBLISH): not wrapped  
[PUNSUBSCRIBE](http://redis.io/commands/PUNSUBSCRIBE): not wrapped  
[QUIT](http://redis.io/commands/QUIT): not wrapped  
[RANDOMKEY](http://redis.io/commands/RANDOMKEY): not wrapped  
[RENAME](http://redis.io/commands/RENAME): not wrapped  
[RENAMENX](http://redis.io/commands/RENAMENX): not wrapped  
[RESTORE](http://redis.io/commands/RESTORE): not wrapped  
[RPOP](http://redis.io/commands/RPOP): wrapped by [Burgov\PredisWrapper\Type\PList::pop](../src/Burgov/PredisWrapper/Type/PList.php#L153)  
[RPOPLPUSH](http://redis.io/commands/RPOPLPUSH): wrapped by [Burgov\PredisWrapper\Type\PList::popAndPushInto](../src/Burgov/PredisWrapper/Type/PList.php#L305)  
[RPUSH](http://redis.io/commands/RPUSH): wrapped by [Burgov\PredisWrapper\Type\PList::push](../src/Burgov/PredisWrapper/Type/PList.php#L130)  
[RPUSHX](http://redis.io/commands/RPUSHX): wrapped by [Burgov\PredisWrapper\Type\PList::push](../src/Burgov/PredisWrapper/Type/PList.php#L130)  
[SADD](http://redis.io/commands/SADD): wrapped by [Burgov\PredisWrapper\Type\Set::add](../src/Burgov/PredisWrapper/Type/Set.php#L14)  
[SAVE](http://redis.io/commands/SAVE): not wrapped  
[SCARD](http://redis.io/commands/SCARD): wrapped by [Burgov\PredisWrapper\Type\Set::count](../src/Burgov/PredisWrapper/Type/Set.php#L35)  
[SCRIPT EXISTS](http://redis.io/commands/SCRIPT EXISTS): not wrapped  
[SCRIPT FLUSH](http://redis.io/commands/SCRIPT FLUSH): not wrapped  
[SCRIPT KILL](http://redis.io/commands/SCRIPT KILL): not wrapped  
[SCRIPT LOAD](http://redis.io/commands/SCRIPT LOAD): not wrapped  
[SDIFF](http://redis.io/commands/SDIFF): wrapped by [Burgov\PredisWrapper\Type\Set::diff](../src/Burgov/PredisWrapper/Type/Set.php#L65)  
[SDIFFSTORE](http://redis.io/commands/SDIFFSTORE): wrapped by [Burgov\PredisWrapper\Type\Set::createFromDiff](../src/Burgov/PredisWrapper/Type/Set.php#L199)  
[SELECT](http://redis.io/commands/SELECT): not wrapped  
[SET](http://redis.io/commands/SET): wrapped by [Burgov\PredisWrapper\Type\Scalar::set](../src/Burgov/PredisWrapper/Type/Scalar.php#L23)  
[SETBIT](http://redis.io/commands/SETBIT): not wrapped  
[SETEX](http://redis.io/commands/SETEX): wrapped by [Burgov\PredisWrapper\Type\Scalar::set](../src/Burgov/PredisWrapper/Type/Scalar.php#L23)  
[SETNX](http://redis.io/commands/SETNX): wrapped by [Burgov\PredisWrapper\Type\Scalar::set](../src/Burgov/PredisWrapper/Type/Scalar.php#L23)  
[SETRANGE](http://redis.io/commands/SETRANGE): wrapped by [Burgov\PredisWrapper\Type\Scalar::setRange](../src/Burgov/PredisWrapper/Type/Scalar.php#L131)  
[SHUTDOWN](http://redis.io/commands/SHUTDOWN): not wrapped  
[SINTER](http://redis.io/commands/SINTER): wrapped by [Burgov\PredisWrapper\Type\Set::intersect](../src/Burgov/PredisWrapper/Type/Set.php#L75)  
[SINTERSTORE](http://redis.io/commands/SINTERSTORE): wrapped by [Burgov\PredisWrapper\Type\Set::createFromIntersect](../src/Burgov/PredisWrapper/Type/Set.php#L209)  
[SISMEMBER](http://redis.io/commands/SISMEMBER): wrapped by [Burgov\PredisWrapper\Type\Set::contains](../src/Burgov/PredisWrapper/Type/Set.php#L96)  
[SLAVEOF](http://redis.io/commands/SLAVEOF): not wrapped  
[SLOWLOG](http://redis.io/commands/SLOWLOG): not wrapped  
[SMEMBERS](http://redis.io/commands/SMEMBERS): wrapped by [Burgov\PredisWrapper\Type\Set::all](../src/Burgov/PredisWrapper/Type/Set.php#L106)  
[SMOVE](http://redis.io/commands/SMOVE): wrapped by [Burgov\PredisWrapper\Type\Set::move](../src/Burgov/PredisWrapper/Type/Set.php#L118)  
[SORT](http://redis.io/commands/SORT): wrapped by [Burgov\PredisWrapper\Type\PList::sort](../src/Burgov/PredisWrapper/Type/PList.php#L54)  
[SPOP](http://redis.io/commands/SPOP): wrapped by [Burgov\PredisWrapper\Type\Set::pop](../src/Burgov/PredisWrapper/Type/Set.php#L128)  
[SRANDMEMBER](http://redis.io/commands/SRANDMEMBER): wrapped by [Burgov\PredisWrapper\Type\Set::randList](../src/Burgov/PredisWrapper/Type/Set.php#L158)  
[SREM](http://redis.io/commands/SREM): not wrapped  
[STRLEN](http://redis.io/commands/STRLEN): wrapped by [Burgov\PredisWrapper\Type\Scalar::getLength](../src/Burgov/PredisWrapper/Type/Scalar.php#L152)  
[SUBSCRIBE](http://redis.io/commands/SUBSCRIBE): not wrapped  
[SUNION](http://redis.io/commands/SUNION): wrapped by [Burgov\PredisWrapper\Type\Set::union](../src/Burgov/PredisWrapper/Type/Set.php#L85)  
[SUNIONSTORE](http://redis.io/commands/SUNIONSTORE): wrapped by [Burgov\PredisWrapper\Type\Set::createFromUnion](../src/Burgov/PredisWrapper/Type/Set.php#L219)  
[SYNC](http://redis.io/commands/SYNC): not wrapped  
[TIME](http://redis.io/commands/TIME): not wrapped  
[TTL](http://redis.io/commands/TTL): not wrapped  
[TYPE](http://redis.io/commands/TYPE): wrapped by [Burgov\PredisWrapper\Client::getType](../src/Burgov/PredisWrapper/Client.php#L72)  
[UNSUBSCRIBE](http://redis.io/commands/UNSUBSCRIBE): not wrapped  
[UNWATCH](http://redis.io/commands/UNWATCH): not wrapped  
[WATCH](http://redis.io/commands/WATCH): not wrapped  
[ZADD](http://redis.io/commands/ZADD): wrapped by [Burgov\PredisWrapper\Type\SortedSet::add](../src/Burgov/PredisWrapper/Type/SortedSet.php#L51)  
[ZCARD](http://redis.io/commands/ZCARD): wrapped by [Burgov\PredisWrapper\Type\SortedSet::count](../src/Burgov/PredisWrapper/Type/SortedSet.php#L34)  
[ZCOUNT](http://redis.io/commands/ZCOUNT): wrapped by [Burgov\PredisWrapper\Type\SortedSet::count](../src/Burgov/PredisWrapper/Type/SortedSet.php#L34)  
[ZINCRBY](http://redis.io/commands/ZINCRBY): wrapped by [Burgov\PredisWrapper\Type\SortedSet::incrementScore](../src/Burgov/PredisWrapper/Type/SortedSet.php#L118)  
[ZINTERSTORE](http://redis.io/commands/ZINTERSTORE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::createFromIntersect](../src/Burgov/PredisWrapper/Type/SortedSet.php#L192)  
[ZRANGE](http://redis.io/commands/ZRANGE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getRange](../src/Burgov/PredisWrapper/Type/SortedSet.php#L224)  
[ZRANGEBYSCORE](http://redis.io/commands/ZRANGEBYSCORE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getRange](../src/Burgov/PredisWrapper/Type/SortedSet.php#L224)  
[ZRANK](http://redis.io/commands/ZRANK): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getRank](../src/Burgov/PredisWrapper/Type/SortedSet.php#L90)  
[ZREM](http://redis.io/commands/ZREM): wrapped by [Burgov\PredisWrapper\Type\SortedSet::remove](../src/Burgov/PredisWrapper/Type/SortedSet.php#L74)  
[ZREMRANGEBYRANK](http://redis.io/commands/ZREMRANGEBYRANK): wrapped by [Burgov\PredisWrapper\Type\SortedSet::removeRange](../src/Burgov/PredisWrapper/Type/SortedSet.php#L275)  
[ZREMRANGEBYSCORE](http://redis.io/commands/ZREMRANGEBYSCORE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::removeRange](../src/Burgov/PredisWrapper/Type/SortedSet.php#L275)  
[ZREVRANGE](http://redis.io/commands/ZREVRANGE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getRange](../src/Burgov/PredisWrapper/Type/SortedSet.php#L224)  
[ZREVRANGEBYSCORE](http://redis.io/commands/ZREVRANGEBYSCORE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getRange](../src/Burgov/PredisWrapper/Type/SortedSet.php#L224)  
[ZREVRANK](http://redis.io/commands/ZREVRANK): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getRank](../src/Burgov/PredisWrapper/Type/SortedSet.php#L90)  
[ZSCORE](http://redis.io/commands/ZSCORE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::getScore](../src/Burgov/PredisWrapper/Type/SortedSet.php#L106)  
[ZUNIONSTORE](http://redis.io/commands/ZUNIONSTORE): wrapped by [Burgov\PredisWrapper\Type\SortedSet::createFromUnion](../src/Burgov/PredisWrapper/Type/SortedSet.php#L178)  
[SCAN](http://redis.io/commands/SCAN): not wrapped  
[SSCAN](http://redis.io/commands/SSCAN): not wrapped  
[HSCAN](http://redis.io/commands/HSCAN): not wrapped  
[ZSCAN](http://redis.io/commands/ZSCAN): not wrapped  
