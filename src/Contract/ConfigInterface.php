<?php

namespace Storage\Contract;


/**
 * Interface ConfigInterface
 * @package Storage\Contract
 */
interface StorageInterface
{
    /**
     * Gets the value for the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null);

    /**
     * Sets a value to the given key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value);

    /**
     * Merges with an array fetched by the given key
     *
     * @param string $key
     * @param array $array An associative array
     * @return mixed
     */
    public static function merge($key, array $array);

    /**
     * Determines whether or not there is any value for a given key
     *
     * @param string $key
     * @return bool
     */
    public static function has($key);
}