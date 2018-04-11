<?php

use Maer\Config\Config;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class LoadTest extends TestCase
{
    protected $config;

    public function testArrayConstructor()
    {
        $this->config = new Config([CONFIGS . '/config.php']);
        $this->assertEquals('foo bar array', $this->config->get('hello.world'), 'Load array');
    }

    public function testJsonConstructor()
    {
        $this->config = new Config([CONFIGS . '/config.json']);
        $this->assertEquals('foo bar json', $this->config->get('hello.world'), 'Load array');
    }

    public function testIniConstructor()
    {
        $this->config = new Config([CONFIGS . '/config.ini']);
        $this->assertEquals('foo bar ini', $this->config->get('hello.world'), 'Load array');
    }

    public function testArrayLoad()
    {
        $this->config = new Config;
        $this->config->load([CONFIGS . '/config.php']);
        $this->assertEquals('foo bar array', $this->config->get('hello.world'), 'Load array');
    }

    public function testJsonLoad()
    {
        $this->config = new Config;
        $this->config->load([CONFIGS . '/config.json']);
        $this->assertEquals('foo bar json', $this->config->get('hello.world'), 'Load array');
    }

    public function testIniLoad()
    {
        $this->config = new Config;
        $this->config->load([CONFIGS . '/config.ini']);
        $this->assertEquals('foo bar ini', $this->config->get('hello.world'), 'Load array');
    }

    public function testOverride()
    {
        $this->config = new Config([
            CONFIGS . '/config.php',
            CONFIGS . '/config_override.php',
        ]);
        $this->assertEquals('original', $this->config->get('root.array.first'), 'Override original');
        $this->assertEquals('override', $this->config->get('root.array.second'), 'Override new');
        $this->assertEquals('foo bar array', $this->config->get('hello.world'), 'Override sibling');
    }
}
