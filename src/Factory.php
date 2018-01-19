<?php

namespace Maer\Config;

/**
 * A simple config package to load files containing multidimensional arrays
 * and fetch them easily using dot notation.
 *
 * @author     Magnus Eriksson <mange@reloop.se>
 * @version    1.1.0
 * @package    Maer
 * @subpackage Config
 */

/**
 * Factory to get the same Config instance
 */
class Factory
{
    /**
     * The Config instance
     * @var Config
     */
    protected static $instance;

    /**
     * This class shouldn't be instanceable. It's a static helper class.
     */
    protected function __construct()
    {
    }


    /**
     * Get the Config instance
     *
     * @return Config
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Config;
        }

        return self::$instance;
    }
}
