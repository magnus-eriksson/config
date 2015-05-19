<?php namespace Maer\Config;

class Config
{
    protected $files = [];
    protected $conf  = [];

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
            // No segments? Then return the complete cache
            return $this->conf;
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
    public function load($files, $forceReload = false)
    {
        if (!is_array($files)) {
            // Make it an array so we can use the same code
            $files = array($files);
        }

        foreach($files as $file) {

            if ((array_key_exists($file, $this->files) && !$forceReload) 
                || !is_file($file)) {
                // It's already loaded, or doesn't exist, so let's skip it
                continue;
            }

            $conf = include $file;

            if (is_array($conf)) {
                // We're only interested if it is an array
                $this->conf         = array_replace_recursive($this->conf, $conf);
                $this->files[$file] = true;
            }
        }
    }

}