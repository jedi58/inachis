<?php
namespace Inachis\Component\CoreBundle\Configuration;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\File\FileHandler;

class ConfigManager
{
    /**
     * @var ConfigManager A {@link ConfigManager} reference to instance of self
     */
    private static $instance;
    /**
     * @var string The filepath for where config files are located
     */
    private static $configLocation;
    /**
     * Default constructor for {@link ConfigManager} - sets the config location
     * when instantiated
     */
    public function __construct()
    {
        self::$configLocation = Application::getApplicationRoot() . 'config/';
    }
    /**
     * Returns an instance of {@link ConfigManager}
     * @return ConfigManager The current or new instance of {@link ConfigManager}
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * Loads in the specific configuration file, using APC if available
     * @param string $filename The name of the file (excluding file extension)
     * @param string $type The file extension for the config file
     * @param string $path The path within the config folder to where files are located
     * @return string[] The result of parsing the config file
     */
    public static function load($filename, $type = 'json', $path = '')
    {
        return FileHandler::loadAndProcessFile(self::$configLocation . $path, $filename . '.' . $type, $type);
    }
    /**
     * Loads all config files within the config folder
     * @param string $type The file extension for the config files to load
     * @return string[] The result of parsing the config files
     */
    public static function loadAll($type = 'json')
    {
        return self::loadAllFromLocation('', $type);
    }
    /**
     * Loads and returns all config files in a given subfolder
     * @param string $path The name of the subfolder to load config files from
     * @param string $type The file extension for the config files
     * @return string[] The result of parsing the config files
     */
    public static function loadAllFromLocation($path, $type = 'json')
    {
        $config = array();
        $files = FileHandler::getAllFilesInFolderOfType(self::$configLocation . $path, $type);
        foreach ($files as $filename) {
            $filename = basename($filename);
            $config[basename($filename, '.' . $type)] = FileHandler::loadAndProcessFile(
                self::$configLocation . $path,
                $filename,
                $type
            );
        }
        return $config;
    }
}
