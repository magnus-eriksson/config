<?php

define('CONFIG1_FILE', __DIR__ . '/test_assets/config1.php');
define('CONFIG2_FILE', __DIR__ . '/test_assets/config2.php');
define('CONFIG_FILE_JSON', __DIR__ . '/test_assets/config.json');
define('CONFIG_FILE_INI', __DIR__ . '/test_assets/config.ini');

/**
 * @coversDefaultClass \Maer\Config\Config
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{

    public $config;


    public function __construct()
    {
        $this->config = new Maer\Config\Config([CONFIG1_FILE]);
    }


    /**
    * @covers ::__construct
    **/
    public function testLoad()
    {
        $result = $this->config->get('file1');
        $this->assertEquals("config1", $result, "Test if config is loaded");

        // Load another
        $this->config->load(CONFIG2_FILE);

        $result = $this->config->get('same');
        $this->assertEquals("file2", $result, "Test duplicate keys overrite");

        // Load Json
        $this->config->load(CONFIG_FILE_JSON);

        $result = $this->config->get('json.says');
        $this->assertEquals("hello", $result, "Test json include");

        // Load ini
        $this->config->load(CONFIG_FILE_INI);

        $result = $this->config->get('iniconfig.says');
        $this->assertEquals("hello ini", $result, "Test ini include");
    }


    /**
    * @covers ::isLoaded
    **/
    public function testIsLoad()
    {
        $result = $this->config->isLoaded('invalid_file.php');
        $this->assertFalse($result, "Test invalid config is loaded");

        $result = $this->config->isLoaded(CONFIG1_FILE);
        $this->assertTrue($result, "Test if config is loaded");
    }


    /**
     * @covers ::get
     */
    public function testGet()
    {
        $result = $this->config->get('level1.level2.level3');
        $this->assertEquals('level3_value', $result, "Test nested");

        $result = $this->config->get('level1.level2');
        $this->assertArrayHasKey('level3', $result, "Test return partial nested");


        $result = $this->config->get('dotlevel1.dotlevel2');
        $this->assertEquals("dotvalue2", $result, "Test return key containing dots. Key with dots has priority.");
    }


    /**
    * @covers Config::set
    **/
    public function testSet()
    {
        $this->config->set('set_me', 'is_set');
        $result = $this->config->get('set_me');
        $this->assertEquals("is_set", $result);

        // Set nested values
        $this->config->set('level1.level2.level3', 'level3_new_value');
        $result = $this->config->get('level1.level2.level3');
        $this->assertEquals('level3_new_value', $result, "Setting nested value");

        $result = $this->config->get('level1.level2.level3-1');
        $this->assertEquals('default_value', $result, "Setting nested value - Sibling");
    }


    /**
    * @covers Config::override
    **/
    public function testOverride()
    {
        $result = $this->config->get('level1.level2.level3-1');
        $this->assertEquals('default_value', $result, "Setting nested value - initial");

        // Set nested values
        $this->config->override([
            'level1' => [
                'level2' => [
                    'level3' => 'level3_new_value'
                ]
            ]
        ]);
        $result = $this->config->get('level1.level2.level3');
        $this->assertEquals('level3_new_value', $result, "Setting nested value");

        $result = $this->config->get('level1.level2.level3-1');
        $this->assertEquals('default_value', $result, "Setting nested value - Sibling");
    }


    /**
    * @covers Config::exists
    **/
    public function testExists()
    {
        $result = $this->config->exists('invalid_key');
        $this->assertFalse($result, "Test non existing key");

        $result = $this->config->exists('file1');
        $this->assertTrue($result, "Test existing key");

        $result = $this->config->exists('level1.level2');
        $this->assertTrue($result, "Test existing nested key");
    }

    /**
    * @covers Config::has
    **/
    public function testHas()
    {
        $result = $this->config->has('invalid_key');
        $this->assertFalse($result, "Test non existing key");

        $result = $this->config->has('file1');
        $this->assertTrue($result, "Test existing key");

        $result = $this->config->has('level1.level2');
        $this->assertTrue($result, "Test existing nested key");
    }

}