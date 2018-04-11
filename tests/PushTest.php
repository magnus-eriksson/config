<?php

use Maer\Config\Config;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class PushTest extends TestCase
{
    protected $config;

    public function testPush()
    {
        $this->config = new Config();

        $this->config->set(['hello' => ['world' => ['first', 'second']]]);
        $this->assertCount(2, $this->config->get('hello.world'));

        $this->config->push('hello.world', 'third');
        $this->assertCount(3, $this->config->get('hello.world'));
    }
}
