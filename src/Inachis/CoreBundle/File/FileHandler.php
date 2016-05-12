<?php

namespace Inachis\Component\CoreBundle\File;

class FileHandler
{
    /**
     *
     */
    public static function getAllFilesInFolder($path)
    {
        return $this->getAllFilesInFolderOfType($path, '*');
    }
    /**
     * Returns an array of files of a given type in the specified folder
     * @param string $path The path to look for files in
     * @param string $type The type of file to look for
     * @return string[] The array of filenames matching the criteria
     */
    public static function getAllFilesInFolderOfType($path, $type)
    {
        return glob($path . '/*.' . $type);
    }
    /**
     *
     */
    public static function loadFile($path, $filename)
    {
        $path = rtrim($path, '/') . '/';
        if (strpos($path, '..') || strpos($filename, '..')) {
            throw new \Exception('Directory traversal not allowed for ' . $path . $filename . PHP_EOL);
        }
        if (!is_file($path . $filename) || !is_readable($path . $filename)) {
            throw new \Exception('Failed to load file: ' . $path . $filename);
        }
        return file_get_contents($path . $filename);
    }
    /**
     *
     */
    public static function loadAndProcessFile($path, $filename, $type = '')
    {
        if ($type == 'json') {
            return self::loadFromJsonFile($path, $filename);
        }
        return self::loadFile($path, $filename);
    }
    /**
     *
     */
    public static function loadFromJsonFile($path, $filename)
    {
        $file = null;
        $key = $path . $filename;// . filemtime($path . $filename);
        if (!function_exists('apc_fetch') || false == ($file = apc_fetch($key))) {
            $file = json_decode(self::loadFile($path, $filename));
            if (function_exists('apc_store')) {
                apc_store($key, $file);
            }
        }
        return $file;
    }
}
