<?php

use Maer\Config\Config;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class MergeTest extends TestCase
{
    protected $config;

    public function testReplace()
    {
        $config = new Config();
        $config->set([
            'root' => [
                'first'  => 'no1',
                'second' => 'no2',
            ]
        ]);

        $config->merge(['root' => ['third' => 'no3']]);

        $this->assertEquals('no1', $config->get('root.first'), 'Merge, existing still exists');
        $this->assertEquals('no3', $config->get('root.third'), 'Merge, new exists');
    }
}
