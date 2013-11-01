PredisWrapper
=============

[![Build Status](https://secure.travis-ci.org/Burgov/PredisWrapper.png?branch=master)](http://travis-ci.org/Burgov/PredisWrapper)
[![Coverage Status](https://coveralls.io/repos/Burgov/PredisWrapper/badge.png?branch=master)](https://coveralls.io/r/Burgov/PredisWrapper?branch=master)

A set of helper classes around the Predis library
```php
$client = new Burgov\PredisWrapper\Client(new Predis\Client(/* ... */));

$string = new Scalar($client, 'string_key');
$set = new Set($client, 'set_key');
$sortedSet = new SortedSet($client, 'sorted_set_key');
$hash = new Hash($client, 'hash_key');
$list = new PList($client, 'list_key'); // unfortunately, "list" is a reserved word in PHP
```

Or use the factory:
```php
$factory = new TypeFactory($client);
// when you're sure "some_key" exists. This will return an instance of the appropriate class
$factory->instantiate('some_key');
// when you're expecting "some_set_key" to be a set or non existent. Will throw exception if it is something else.
$factory->instantiateSet('some_set_key');
```

`$factory` will always try to return the same instance of a type based on the key

See usage examples in the integration tests:
  - [Scalar](tests/Burgov/PredisWrapper/Integration/ScalarTest.php)
  - [Hash](tests/Burgov/PredisWrapper/Integration/HashTest.php)
  - [List](tests/Burgov/PredisWrapper/Integration/PListTest.php)
  - [Set](tests/Burgov/PredisWrapper/Integration/SetTest.php)
  - [SortedSet](tests/Burgov/PredisWrapper/Integration/SortedSetTest.php)
