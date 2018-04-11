<?php

use Maer\Config\Config;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class ReadersTest extends TestCase
{
    protected $config;

    public function testReaderConstructor()
    {
        $options = [
            'readers' => [
                'mock' => new MockReader,
            ],
        ];

        $this->config = new Config([CONFIGS . '/config.mock'], $options);
        $this->assertEquals('mockingbird', $this->config->get('hello.world'), 'reader constructor');
    }

    public function testReaderMethod()
    {
        $this->config = new Config;
        $this->config->setReader('mock', new MockReader);
        $this->config->load(CONFIGS . '/config.mock');
        $this->assertEquals('mockingbird', $this->config->get('hello.world'), 'reader method');
    }
}
