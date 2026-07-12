<?php

/**
 * Simple configuration loader for INI files.
 *
 * Reads settings from a configuration file and exposes them through a getter.
 */
class Codex
{
    /**
     * Parsed configuration values.
     *
     * @var array
     */
    private $params = [];

    /**
     * Create a new Codex instance.
     *
     * @param string $file Path to the INI file.
     */
    public function __construct($file = 'config.ini')
    {
        // Load configuration when the file exists.
        if (file_exists($file)) {
            $this->params = parse_ini_file($file);
        }
    }

    /**
     * Retrieve a configuration value by key.
     *
     * @param string $key Configuration key.
     * @return mixed
     */
    public function get($key)
    {
        // Return the stored value or false when it is not set.
        return $this->params[$key] ?? false;
    }
}