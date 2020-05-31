<?php declare(strict_types = 1);

namespace Xo\Storage;


use InvalidArgumentException;
use Xo\Storage\Contract\StorageInterface;

use function file_exists;
use function is_writeable;
use function is_array;
use function array_merge;
use function array_key_exists;
use function fopen;
use function fwrite;
use function fclose;
use function json_encode;
use function json_decode;
use function file_get_contents;
use function file_put_contents;
use function unlink;


/**
 * Class Storage
 * @package Xo\Storage
 */
final class Storage implements StorageInterface
{
    /**
     * Holds the file location with the filename
     *
     * @var string
     */
    private static $location;

    /**
     * Holds array data to keys
     *
     * @var array[]
     */
    private static $data = [];

    /**
     * Storage constructor.
     *
     * @param string $location
     */
    private function __construct(string $location)
    {
        if (! file_exists($location) || ! is_writeable($location)) {
            throw new InvalidArgumentException('File must be existed or writable');
        }

        self::$location = $location;

        $resultingArray = self::read($location);
        if (is_array($resultingArray)) {
            self::$data = $resultingArray;
        }
    }

    /**
     * Creates Storage instance
     *
     * @param string $location The location with the filename
     * @return Storage
     */
    public static function getInstance(string $location): Storage
    {
        return new static($location);
    }

    /**
     * Stores array data to a given file
     *
     * @param array[] $data
     * @param string $location
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function create(array $data, string $location): bool
    {
        if (! file_exists($location) || ! is_writeable($location)) {
            throw new InvalidArgumentException('File must be existed or writable');
        }

        $fh = @fopen($location, 'w+');

        if ($fh) {
            $encodedData = json_encode($data);
            if ($encodedData) {
                $done = fwrite($fh, $encodedData);
                fclose($fh);

                if (false !== $done) {
                    self::$data = $data;
                }

                return (false !== $done);
            }
        }

        return false;
    }

    /**
     * Gets the value for the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        if (array_key_exists($key, self::$data)) {
            return self::$data[$key];
        }

        return $default;
    }

    /**
     * Sets a value to the given key
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, $value): bool
    {
        if (file_exists(self::$location)) {
            self::$data[$key] = $value;
            $done = self::modify(self::$data);

            return $done;
        }

        return false;
    }

    /**
     * Updates an array fetched by the given key
     *
     * @param string $key
     * @param array[] $array An associative array
     * @return bool
     */
    public static function update(string $key, array $array): bool
    {
        if (file_exists(self::$location)) {
            $chunkOfArray = self::$data[$key];
            if (is_array($chunkOfArray)) {
                $resultingArray = array_merge($chunkOfArray, $array);
                self::$data[$key] = $resultingArray;
                $done = self::modify(self::$data);

                return $done;
            }
        }

        return false;
    }

    /**
     * Determines whether or not there is any value for a given key
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset(self::$data[$key]);
    }

    /**
     * Removes array data based on the given key
     *
     * @param string $key
     * @return bool
     */
    public static function remove(string $key): bool
    {
        if (array_key_exists($key, self::$data)) {
            unset(self::$data[$key]);
            $done = self::modify(self::$data);

            return $done;
        }

        return false;
    }

    /**
     * Empties the file
     *
     * @return bool
     */
    public static function clear(): bool
    {
        if (file_exists(self::$location)) {
            $done = file_put_contents(self::$location, '', LOCK_EX);
            if (false !== $done) {
                self::$data = [];
            }

            return (false !== $done);
        }

        return false;
    }

    /**
     * Deletes the file
     *
     * @return bool
     */
    public static function delete(): bool
    {
        if (file_exists(self::$location)) {
            $done = unlink(self::$location);
            if ($done) {
                self::$data = [];
                self::$location = '';
            }

            return $done;
        }

        return false;
    }

    /**
     * Displays all data
     *
     * @return array[]
     */
    public static function all(): array
    {
        return self::$data;
    }

    /**
     * Reads all data
     *
     * @param string $location
     * @return array[]
     */
    private static function read(string $location): array
    {
        $array = [];
        $json = file_get_contents($location);

        if ($json) {
            $array = json_decode($json, true);
        }

        return $array;
    }

    /**
     * Updates data
     *
     * @param array[] $data
     * @return bool
     */
    private static function modify(array $data): bool
    {
        if (file_exists(self::$location)) {
            $encodedData = json_encode($data);
            $done = file_put_contents(self::$location, $encodedData, LOCK_EX);

            return (false !== $done);
        }

        return false;
    }
}