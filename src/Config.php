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

use RecursiveIteratorIterator;
use RecursiveArrayIterator;

class Config implements ConfigInterface
{
    protected $files = [];
    protected $conf  = [];


    /**
     * {@inheritdoc}
     */
    public function __construct(array $files = array())
    {
        if ($files) {
            $this->load($files);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function get($key = null, $default = null)
    {
        if (!$key) {
            return $default;
        }
        
        // If we have a direct match, return it.
        // This makes it possible to have keys containing dots
        if (array_key_exists($key, $this->conf)) {
            return $this->conf[$key];
        }

        $conf  =& $this->conf;

        foreach(explode('.', $key) as $segment) {
            
            if (!array_key_exists($segment, $conf)) {
                return $default;
            }

            $conf =& $conf[$segment];
        
        }

        return $conf;
    }


    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $conf =& $this->conf;

        $segments = explode('.', $key);

        while (count($segments) > 1)
        {
            $segment = array_shift($segments);
            if ( ! isset($conf[$segment]) || ! is_array($conf[$segment])) {
                $conf[$segment] = array();
            }
            $conf =& $conf[$segment];
        }

        return $conf[array_shift($segments)] = $value;
    }


    /**
     * {@inheritdoc}
     */
    public function override(array $array)
    {
        $this->merge($array);
    }


    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        $conf  =& $this->conf;

        foreach(explode('.', $key) as $segment) {
            
            if (!array_key_exists($segment, $conf)) {
                return false;
            }

            $conf =& $conf[$segment];
        
        }

        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->exists($key);
    }


    /**
     * {@inheritdoc}
     */
    public function load($files, $forceReload = false)
    {
        if (!is_array($files)) {
            // Make it an array so we can use the same code
            $files = array($files);
        }

        foreach($files as $file) {

            if ((array_key_exists($file, $this->files) && !$forceReload) 
                || !is_file($file) || !is_readable($file)) {
                // It's already loaded, or doesn't exist, so let's skip it
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            $conf = $ext == "json"
                ? json_decode(file_get_contents($file), true, 512)
                : include $file;

            if (is_array($conf)) {
                // We're only interested if it is an array
                $this->override($conf);
                $this->files[$file] = true;
            }

            unset($conf);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function isLoaded($file)
    {
        return array_key_exists($file, $this->files);
    }


    /**
     * Recursivly merge the array to the existing collection
     * 
     * @param  array $array
     * @return array
     */
    protected function merge(array $array)
    {
        $ritit  = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));

        foreach($ritit as $leafValue) {
            $keys = array();
            foreach (range(0, $ritit->getDepth()) as $depth) {
                $keys[] = $ritit->getSubIterator($depth)->key();
            }
            $this->set(join('.', $keys), $leafValue);
        }
    }
}