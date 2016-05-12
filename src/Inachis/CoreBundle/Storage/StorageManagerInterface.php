<?php

namespace Inachis\Component\CoreBundle\Storage;

/**
 * Interface which when implemented, stores values associated with the current session
 * or application state
 */
interface StorageManagerInterface
{
    /**
     * Returns a value from the specified key
     * @param string $key The name of the key to fetch the value for
     * @return mixed The value stored by {@link $key}
     */
    public function get($key);
    /**
     * Sets a value for the specified key
     * @param string $key The name of the key to fetch the value for
     * @param mixed $value The value to associate with the key
     */
    public function set($key, $value);
    /**
     * Removes the specified key/value pair
     * @param string $key The name of the key to remove
     */
    public function remove($key);
    /**
     * Determine if key is set and is not null
     * @param string $key The name of the key to fetch the value for
     * @return bool Result of testing if the key exists and is not null
     */
    public function hasKey($key);
}
