<?php namespace Maer\Config;

interface ConfigInterface
{
    /**
     * Create a new instance
     *
     * @param  array    $files  Array of absolut paths to files that should be loaded upon instantiation
     * @param  array    $options
     */
    public function __construct(array $files = [], array $options = []);


    /**
     * Get a value from the loaded config files
     *
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @param  mixed    $default    Returned if key is not found
     *
     * @return mixed    Value|$default
     */
    public function get($key = null, $default = null);


    /**
     * Set new or overwrite existing values
     *
     * @param  string|array $key   If array, it's an alias for override. If string, use dot notation for nested config arrays.
     * @param  mixed        $value Value to set
     *
     * @return mixed        $value
     */
    public function set($key, $value = null);


    /**
     * Push an item into an array
     *
     * @param  string   $key        Key, use dot notation for nested config arrays.
     * @param  mixed    $value      Value to push
     *
     * @throws \UnexpectedValueException if the target isn't an array
     *
     * @return void
     */
    public function push($key, $value);


    /**
     * Merge multiple values from array. Alias for merge()
     *
     * @param  array    $values
     *
     * @deprecated An old alias. Use Config::merge() instead
     *
     * @return void
     */
    public function override(array $values);


    /**
     * Merge multiple values from array
     *
     * @param  array    $values
     *
     * @return void
     */
    public function merge(array $values);


    /**
     * Check if a key exists in the loaded config files. Alias for Config::has()
     *
     * @param  string   $key        Key, use dot notation for nested config arrays.
     *
     * @deprecated An old alias. Use Config::has() instead
     *
     * @return boolean
     */
    public function exists($key);


    /**
     * Check if a key exists in the loaded config files
     *
     * @param  string   $key        Key, use dot notation for nested config arrays.
     *
     * @return boolean
     */
    public function has($key);


    /**
     * Load one or more config files
     *
     * @param  string|array  $files         Absolute paths to the config files
     * @param  boolean       $forceReload   If true, the file will be re-read if it already has been loaded
     *
     * @return void
     */
    public function load($files, $forceReload = false);


    /**
     * Check if a config file has been loaded
     *
     * @param  string   $file   Absolute path to the config file
     *
     * @return boolean
     */
    public function isLoaded($file);

    /**
     * Add a new reader
     *
     * @param string                  $extension Associated file extension
     * @param Readers\ReaderInterface $reader
     */
    public function setReader($extension, Readers\ReaderInterface $reader);
}
