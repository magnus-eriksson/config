<?php

use Maer\Config\Config;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class HelpersTest extends TestCase
{
    protected $config;

    public function testHas()
    {
        $this->config = new Config([CONFIGS . '/config.php']);

        $this->assertFalse($this->config->has('hello.world.test'));
        $this->assertTrue($this->config->has('hello.world'));
    }

    public function testIsLoaded()
    {
        $this->config = new Config([CONFIGS . '/config.php']);

        $this->assertFalse($this->config->isLoaded(CONFIGS . '/config_nonexsting.php'), 'isloaded fail');
        $this->assertTrue($this->config->isLoaded(CONFIGS . '/config.php'), 'isloaded success');
    }
}
