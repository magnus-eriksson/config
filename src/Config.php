<?php namespace Maer\Config;

use UnexpectedValueException;
use Maer\Config\Readers;

class Config implements ConfigInterface
{
    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $conf  = [];

    /**
     * @var array
     */
    protected $readers = [];


    /**
     * {@inheritdoc}
     */
    public function __construct(array $files = [], array $options = [])
    {
        $this->setReader('php', $options['readers']['php'] ?? new Readers\ArrayReader);
        $this->setReader('ini', $options['readers']['ini'] ?? new Readers\IniReader);
        $this->setReader('json', $options['readers']['json'] ?? new Readers\JsonReader);

        foreach ($options['readers'] ?? [] as $ext => $reader) {
            $this->setReader($ext, $reader);
        }

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

        $conf =& $this->conf;
        $keys = explode('.', $key);

        foreach ($keys as $test) {
            $direct = implode('.', $keys);

            // Check for a direct match, containing dot
            if (is_array($conf) && array_key_exists($direct, $conf)) {
                return $conf[$direct];
            }

            if (!is_array($conf) || !array_key_exists($test, $conf)) {
                // No hit, return the default
                return $default;
            }

            $conf =& $conf[$test];
            array_shift($keys);
        }

        return $conf;
    }


    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            return $this->merge($key);
        }

        if (func_num_args() < 2) {
            throw new \ArgumentCountError("To few arguments passed to Config::set(). Expected 2 got 1");
        }

        $conf =& $this->conf;

        $segments = explode('.', $key);

        while (count($segments) > 1) {
            $segment = array_shift($segments);
            if (!isset($conf[$segment]) || !is_array($conf[$segment])) {
                $conf[$segment] = [];
            }
            $conf =& $conf[$segment];
        }

        return $conf[array_shift($segments)] = $value;
    }


    /**
     * {@inheritdoc}
     */
    public function push($key, $value)
    {
        $items = $this->get($key);
        if (!is_array($items)) {
            throw new UnexpectedValueException("Expected the target to be array, got " . gettype($items));
        }

        $items[] = $value;
        $this->set($key, $items);
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
    public function has($key)
    {
        $conf =& $this->conf;
        $keys = explode('.', $key);

        foreach ($keys as $test) {
            $direct = implode('.', $keys);

            // Check for a direct match, containing dot
            if (is_array($conf) && array_key_exists($direct, $conf)) {
                return true;
            }

            if (!is_array($conf) || !array_key_exists($test, $conf)) {
                return false;
            }

            $conf =& $conf[$test];
            array_shift($keys);
        }

        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return $this->has($key);
    }


    /**
     * {@inheritdoc}
     */
    public function load($files, $forceReload = false)
    {
        if (!is_array($files)) {
            // Make it an array so we can use the same code
            $files = [$files];
        }

        foreach ($files as $file) {
            if ((array_key_exists($file, $this->files) && !$forceReload)
                || !is_file($file) || !is_readable($file)) {
                // It's already loaded, or doesn't exist, so let's skip it
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (!array_key_exists($ext, $this->readers)) {
                throw new \Exception("No reader found for the extension '{$ext}'");
            }

            $conf = $this->readers[$ext]->read($file);

            if ($conf && is_array($conf)) {
                // We're only interested if it is a non-empty array
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
     * {@inheritdoc}
     */
    public function setReader($extension, Readers\ReaderInterface $reader)
    {
        $this->readers[strtolower($extension)] = $reader;
    }


    /**
     * Recursivly merge the array to the existing collection
     *
     * @param  array $array
     */
    protected function merge(array $array)
    {
        $this->conf = array_replace_recursive($this->conf, $array);
    }
}
