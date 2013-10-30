PredisWrapper
=============

[![Build Status](https://secure.travis-ci.org/Burgov/PredisWrapper.png?branch=master)](http://travis-ci.org/Burgov/PredisWrapper)
[![Coverage Status](https://coveralls.io/repos/Burgov/PredisWrapper/badge.png?branch=master)](https://coveralls.io/r/Burgov/PredisWrapper?branch=master)

A set of helper classes around the Predis library

```php

$client = new Burgov\PredisWrapper\Client(new Predis\Client(/* ... */));

$string = new Scalar($client, 'string_key');
$set = new Set($client, 'set_key');
$hash = new Hash($client, 'hash_key');
$list = new PList($client, 'list_key'); // unfortunately, "list" is a reserved word in PHP
```

See usage examples in the integration tests:
[Scalar](tests/Burgov/PredisWrapper/Integration/ScalarTest.php)
[Hash](tests/Burgov/PredisWrapper/Integration/HashTest.php)
[List](tests/Burgov/PredisWrapper/Integration/PListTest.php)
[Set](tests/Burgov/PredisWrapper/Integration/SetTest.php)
