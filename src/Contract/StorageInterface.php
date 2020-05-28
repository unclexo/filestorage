<?php

namespace Xo\Storage\Contract;


/**
 * Interface StorageInterface
 * @package Xo\Storage\Contract
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
     * @return bool
     */
    public static function set($key, $value);

    /**
     * Updates an array fetched by the given key
     *
     * @param string $key
     * @param array $array An associative array
     * @return bool
     */
    public static function update($key, array $array);

    /**
     * Determines whether or not there is any value for a given key
     *
     * @param string $key
     * @return bool
     */
    public static function has($key);

    /**
     * Removes array data based on the given key
     *
     * @param string $key
     * @return bool
     */
    public static function remove($key);
}