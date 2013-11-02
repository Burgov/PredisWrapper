<?php

namespace Burgov\PredisWrapper\Integration;

use Burgov\PredisWrapper\Type\PList;
use Burgov\PredisWrapper\TypeFactory;
use Symfony\Component\Process\Process;

class PListTest extends AbstractIntegrationTest
{
    protected function setUpDatabase()
    {

    }

    public function testIntegration()
    {
        $factory = new TypeFactory($this->client);
        $list = $factory->instantiate('list', 'list');

        $list[] = 'a';
        $list[] = 'b';
        $list[] = 'c';
        $this->assertEquals(array('a', 'b', 'c'), iterator_to_array($list));
        $this->assertCount(3, $list);

        $list->shift();
        $list->pop();
        $list->push('d');
        $list->unshift('z');
        $this->assertEquals(array('z', 'b', 'd'), iterator_to_array($list));

        $list[] = 'b';
        $list->remove('b');
        $this->assertEquals(array('z', 'd'), iterator_to_array($list));

        $list->insert('a', PList::BEFORE, 'd');
        $list->insert('b', PList::AFTER, 'a');
        $this->assertEquals(array('z', 'a', 'b', 'd'), iterator_to_array($list));

        $list->trim(1, 2);
        $this->assertEquals(array('a', 'b'), iterator_to_array($list));

        $list[1] = 'c';
        $this->assertEquals(array('a', 'c'), iterator_to_array($list));

        $list2 = new PList($this->client, 'list2');
        $this->assertCount(0, $list2);

        $list->popAndPushInto($list2);
        $this->assertCount(1, $list);
        $this->assertCount(1, $list2);
        $this->assertEquals(array('a'), iterator_to_array($list));
        $this->assertEquals(array('c'), iterator_to_array($list2));

        $list3 = new PList($this->client, 'list3');
        $this->assertCount(0, $list3);
        $list3->push('a', true);
        $list3->unshift('a', true);
        $this->assertCount(0, $list3);
    }

    private function startProcess($command, $timeout)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            $timeoutCommand = "ping -n " . ($timeout + 1) . " 127.0.0.1 > nul";
        } else {
            $timeoutCommand = "sleep 1";
        }
        $redisCommand = sprintf(
            "%s -h %s -p %s -n %d -x",
            $_SERVER['REDIS_CLI_EXECUTABLE'],
            $_SERVER['REDIS_SERVER_HOST'],
            $_SERVER['REDIS_SERVER_PORT'],
            $_SERVER['REDIS_SERVER_DBNUM']
        );

        $process = new Process(implode(" && ", array($timeoutCommand, $redisCommand)));
        $process->setStdin($command);
        $process->start();
        return $process;
    }

    /**
     * @medium
     */
    public function testBlockMethods()
    {
        $process = $this->startProcess('rpush list1 value1', 1);

        $list1 = new PList($this->client, 'list1');
        $list2 = new PList($this->client, 'list2');

        // make sure we indeed waited approximately 1 second
        $t = microtime(true);
        $this->assertEquals(array('list1' => 'value1'), PList::blockPopMulti(array($list1, $list2), 3));
        $this->assertEquals(1, round(microtime(true) - $t));

        $process->wait();
        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());

        $this->assertCount(0, iterator_to_array($list1));

        $process = $this->startProcess('rpush list2 value2', 1);

        $t = microtime(true);
        $this->assertEquals(array('list2' => 'value2'), PList::blockShiftMulti(array($list1, $list2), 3));
        $this->assertEquals(1, round(microtime(true) - $t));

        $process->wait();
        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());

        $process = $this->startProcess('rpush list1 value1', 1);
        $list1->blockPopAndPushInto($list2, 2);

        $process->wait();
        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());

        $this->assertEquals(array(), iterator_to_array($list1));
        $this->assertEquals(array('value1'), iterator_to_array($list2));
    }
}
