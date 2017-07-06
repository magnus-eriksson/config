<?php

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class PushTest extends PHPUnit_Framework_TestCase
{
    public $config;


    public function __construct()
    {
        $this->config = new Maer\Config\Config();

        $this->config->set('nested.array', ['first']);
        $this->config->set('nested.non_array', 'Hello');
    }


    /**
    * @covers ::__construct
    **/
    public function testPushToArray()
    {
        // Make sure it contains what we think it contains
        $count = count($this->config->get('nested.array'));
        $this->assertEquals(1, $count);

        // Push and check again
        $this->config->push('nested.array', 'second');

        $count = count($this->config->get('nested.array'));
        $this->assertEquals(2, $count);

        // Check that the value is correct
        $this->assertEquals('second', $this->config->get('nested.array.1'));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testPushException()
    {
        $this->config->push('nested.non_array', 'testing');
    }
}
