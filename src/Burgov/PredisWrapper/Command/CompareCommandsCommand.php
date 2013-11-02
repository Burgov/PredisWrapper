<?php

namespace Burgov\PredisWrapper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class CompareCommandsCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('compare-commands')
            ->setDescription('Check all wrapped methods against methods described in the redis documentation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $redisCommands = $this->getRedisCommands();
        $wrapperCommands = $this->getWrapperCommands();

        $md = "";

        foreach ($redisCommands as $redisCommand) {
            $redisCommandString = sprintf("[%s](http://redis.io/commands/%s)", $redisCommand, $redisCommand);
            $methodWrapString = array_key_exists($redisCommand, $wrapperCommands) ? sprintf(
                'wrapped by [%s::%s](../src/%s.php#L%s)',
                $wrapperCommands[$redisCommand][0],
                $wrapperCommands[$redisCommand][1],
                str_replace("\\", "/", $wrapperCommands[$redisCommand][0]),
                $wrapperCommands[$redisCommand][2]
            ) : 'not wrapped';
            $md .= sprintf("%s: %s  \n", $redisCommandString, $methodWrapString);
        }

        $output->write($md);
    }

    private function getRedisCommands()
    {
        $html = file_get_contents('http://redis.io/commands');
        $crawler = new Crawler($html);

        return $crawler->filter('span.command > a')->each(function (Crawler $node) {
            return $node->text();
        });
    }

    private function getWrapperCommands()
    {
        $commands = array();

        foreach (array(
             'Client', 'Type\\AbstractListType', 'Type\\AbstractType',
             'Type\\Scalar', 'Type\\Set', 'Type\\SortedSet', 'Type\\PList', 'Type\\Hash'
         ) as $type) {
            $refl = new \ReflectionClass('Burgov\\PredisWrapper\\'.$type);

            foreach ($refl->getMethods() as $method) {
                $docblock = $method->getDocComment();
                if (false === $docblock) {
                    continue;
                }

                if (!preg_match('/Wraps commands? (.*?)\r?\n/', $docblock, $m)) {
                    continue;
                }

                preg_match_all('/[A-Z]+/', $m[1], $m);

                foreach ($m[0] as $command) {
                    $commands[$command] = array(
                        $refl->getName(),
                        $method->getName(),
                        $method->getStartLine()
                    );
                }
            }
        }

        return $commands;
    }
}
