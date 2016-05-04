<?php namespace Maer\Config;
/**
 * A simple config package to load files containing multidimensional arrays 
 * and fetch them easily using dot notation.
 * 
 * @author     Magnus Eriksson <mange@reloop.se>
 * @version    1.1.0
 * @package    Maer
 * @subpackage Config
 */

interface ConfigInterface
{
    /**
     * Create a new instance
     * 
     * @param  array    $files  Array of absolut paths to files that should be loaded upon instantiation
     */
    public function __construct(array $files = array());


    /**
     * Get a value from the loaded config files
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @param  mixed    $default    Returned if key is not found
     * @return mixed    Value|$default
     */
    public function get($key = null, $default = null);


    /**
     * Set a new or overwrite an existing value
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @param  mixed    $value      Value to set
     * @return mixed    $value
     */
    public function set($key, $value);


    /**
     * Set multiple values from array
     * 
     * @param  array    $values
     * @return void
     */
    public function override(array $values);


    /**
     * Check if a key exists in the loaded config files
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @return boolean
     */
    public function exists($key);


    /**
     * Alias for Config::exists
     * 
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @return boolean
     */
    public function has($key);


    /**
     * Load one or more config files
     * 
     * @param  string|array  $files         Absolute paths to the config files
     * @param  boolean       $forceReload   If true, the file will be re-read if it already has been loaded
     * @return void
     */
    public function load($files, $forceReload = false);


    /**
     * Check if a config file has been loaded
     * 
     * @param  string   $file   Absolute path to the config file
     * @return boolean
     */
    public function isLoaded($file);
}