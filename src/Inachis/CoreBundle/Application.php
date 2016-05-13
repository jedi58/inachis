<?php

namespace Inachis\Component\CoreBundle;

use Inachis\Component\CoreBundle\Configuration\ConfigManager;
use Inachis\Component\CoreBundle\Routing\RoutingManager;

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
     * @var RoutingManager Instance used for handling Application routing
     */
    protected $router;
    /**
     * @var string[] Array of configurations for the application
     */
    protected $config = array();
    /**
     * @var mixed[] Array of services for the application to access
     */
    protected $services = array();
    /**
     * Default constructor for {@link Application} which will instantiate required
     * services.
     * @param string $env The environment type being used
     */
    public function __construct($env = 'dev')
    {
        $this->setEnv($env);
        $this->services['configManager'] = ConfigManager::getInstance();
        $this->config = $this->services['configManager']->loadAll();
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
     * Adds a service to the application
     * @param mixed $service The service to add
     */
    public function addService($service)
    {
        $this->services[] = $service;
    }
    /**
     * Returns the array of registered services
     * @return mixed[] The array of services
     */
    public function getServices()
    {
        return $this->services;
    }
    /**
     * Returns a specific service
     * @param string $service The service to return
     * @return mixed The requested service
     */
    public function getService($service)
    {
        return $this->hasService($service) ? $this->services[(string) $service] : null;
    }
    /**
     * Determines if a service has been registered
     * @param string $service The name of the service to check for
     * @return bool The result of checking for the service
     */
    public function hasService($service)
    {
        return array_key_exists((string) $service, $this->services);
    }
    /**
     * Returns the path to the root of where the application has been installed
     * @return string The filepath to where the application is installed
     */
    public static function getApplicationRoot()
    {
        return str_replace('src/Inachis/CoreBundle', '', __DIR__);
    }
}
