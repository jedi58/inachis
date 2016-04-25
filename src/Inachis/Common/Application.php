<?php

namespace Inachis\Component\Common;

use Inachis\Component\Common\Configuration\ConfigManager;
use Inachis\Component\Common\Routing\RoutingManager;

/**
 * Class used to control the application
 */
class Application
{
    /**
     * @const string[] The allowed mode types for the environment
     */
    const MODES = array('dev', 'test', 'preprod', 'prod');
    /**
     * @var Application reference to instance of self
     */
    private static $instance;
    /**
     * @var string The mode for the current environment; limited to {@link Application::MODES}
     */
    protected $env = 'dev';
    /**
     * @var ConfigManager Instance used for handling Application config
     */
    protected $config;
    /**
     * @var RoutingManager Instance used for handling Application routing
     */
    protected $router;
    /**
     * Default constructor for {@link Application} which will instantiate required
     * services.
     * @param string $env The environment type being used
     */
    public function __construct($env = 'dev')
    {
        $this->setEnv($env);
        $this->config = ConfigManager::getInstance();
        $this->config->loadAll();
        $this->router = RoutingManager::getInstance();
        $this->router->load();
    }
    /**
     * Returns an instance of {@link Application}
     * @param string $env The environment type being used
     * @return Application The current or a new instance of {@link Application}
     */
    public static function getInstance($env = 'dev')
    {
        if (null === static::$instance) {
            static::$instance = new static($env);
        }
        return static::$instance;
    }
    /**
     * Returns the current environment mode
     * @return string The current environment
     */
    public function getEnv()
    {
        return $this->env;
    }
    /**
     * Returns the {@link Application}'s {@link RoutingManager} object
     * @return RoutingManager The instance of the routing manager
     */
    public function getRouter()
    {
        return $this->router;
    }
    /**
     * Returns the {@link Application}'s {@link ConfigManager} object
     * @return ConfigManager The instance of the config manager
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * Sets the current environment mode to the specified mode
     * @param string $value The mode to use
     * @throws InvalidEnvironmentException
     */
    public function setEnv($value)
    {
        if (!in_array($value, self::MODES)) {
            throw new Exception\InvalidEnvironmentException('Mode ' . $value . ' not supported');
        }
        $this->env = $value;
    }
    /**
     * Returns the path to the root of where the application has been installed
     * @return string The filepath to where the application is installed
     */
    public static function getApplicationRoot()
    {
        return str_replace('src/Inachis/Common', '', __DIR__);
    }
}
