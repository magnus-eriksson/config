<?php

use Maer\Config\Config;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class GetSetTest extends TestCase
{
    protected $config;

    public function testGet()
    {
        $this->config = new Config([CONFIGS . '/config.php']);
        $array  = $this->config->get('root.array');
        $string = $this->config->get('root.array.first');

        $this->assertInternalType('array', $array, 'get array');
        $this->assertInternalType('string', $string, 'get string');

        $this->assertEquals('original', $string);
        $this->assertEquals($array['first'] ?? '', $string);
    }

    public function testSet()
    {
        $this->config = new Config([CONFIGS . '/config.php']);
        $this->config->set('root.array.second', 'hello second');

        $this->assertInternalType('array', $this->config->get('root.array'), 'get array after set');
        $this->assertEquals('hello second', $this->config->get('root.array.second'), 'get string after set');
    }

    public function testOverride()
    {
        $this->config = new Config([CONFIGS . '/config.php',]);
        $array = include CONFIGS . '/config_override.php';
        $this->config->set($array);

        $this->assertEquals('original', $this->config->get('root.array.first'), 'Override original');
        $this->assertEquals('override', $this->config->get('root.array.second'), 'Override new');
        $this->assertEquals('foo bar array', $this->config->get('hello.world'), 'Override sibling');
    }
}
