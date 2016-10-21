<?php

namespace Inachis\Component\CoreBundle;

use Inachis\Component\CoreBundle\Configuration\ConfigManager;
use Inachis\Component\CoreBundle\Routing\RoutingManager;
use Inachis\Component\CoreBundle\Security\Authentication;
use Inachis\Component\CoreBundle\Security\Encryption;
use Inachis\Component\CoreBundle\Security\SessionManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class used to control the application
 */
class Application
{
    /**
     * @const string[] The allowed mode types for the environment
     */
    public static $MODES = array('dev', 'test', 'preprod', 'prod');
    /**
     * @var Application reference to instance of self
     */
    private static $instance;
    /**
     * @var string The mode for the current environment; limited to {@link Application::MODES}
     */
    protected $env = 'dev';
    /**
     * @var bool Flag indicating if admin actions should be logged
     */
    protected $logActivities = false;
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
     * @throws \Exception
     */
    public function __construct($env = 'dev')
    {
        $this->setEnv($env);
        $this->services['configManager'] = ConfigManager::getInstance();
        $this->config = $this->services['configManager']->loadAll();
        $this->router = RoutingManager::getInstance();
        $this->router->load();

        $config = new Configuration();
        $config->setProxyDir($this->getApplicationRoot() . 'app/persistent/proxies');
        $config->setProxyNamespace('proxies');
        $config->setAutoGenerateProxyClasses(($env === 'dev'));

        if (empty($this->config['system'])) {
            throw new \Exception('System configuration could not be loaded. Please check config/system.json');
        }

        if (!empty($this->getConfig()['system']->general->activity_tracking)) {
            $this->logActivities = (bool) $this->getConfig()['system']->general->activity_tracking;
        }

        AnnotationRegistry::registerFile(
            $this->getApplicationRoot() . 'vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
        );
        $reader = new AnnotationReader();
        $driverImpl = new AnnotationDriver($reader, array( __DIR__ . '/Entity/'));
        $config->setMetadataDriverImpl($driverImpl);
        $this->services['em'] = EntityManager::create((array) $this->config['system']->repository, $config);
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
     * @throws Exception\InvalidEnvironmentException
     */
    public function setEnv($value)
    {
        if (!in_array($value, self::$MODES)) {
            throw new Exception\InvalidEnvironmentException('Mode ' . $value . ' not supported');
        }
        $this->env = $value;
    }

    /**
     * Adds a service to the application
     * @param string $name The name of the service to add
     * @param mixed $service The service to add
     * @throws \Exception
     */
    public function addService($name, $service)
    {
        if (array_key_exists($name, $this->services)) {
            throw new \Exception(sprintf('Service %s already added', $name));
        }
        $this->services[$name] = $service;
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
    public function requireSessionService()
    {
        if (!$this->hasService('session')) {
            $this->addService('session', new SessionManager());
        }
        return $this->getService('session');
    }
    /**
     * Automatically adds a new authentication service if one has not already been registered
     * @return Authentication The registered authentication service
     */
    public function requireAuthenticationService()
    {
        if (!$this->hasService('auth')) {
            $this->requireSessionService();
            $this->requireEncryptionService();
            $this->addService('auth', new Authentication());
        }
        return $this->getService('auth');
    }

    /**
     * Automatically adds a new encryption service if one has not already been registered
     * @return Encryption The registered encryption service
     * @throws \Exception
     */
    public function requireEncryptionService()
    {
        if (!$this->hasService('encryption')) {
            if (empty($key = Application::getInstance()->getConfig()['system']->security->encryptionKey)) {
                throw new \Exception(
                    'Config error - encryption key not found. Generate one using `gulp encryption:generate-key`'
                );
            }
            $this->addService('encryption', new Encryption($key));
        }
        return $this->getService('encryption');
    }
    /**
     * Determines if admin interactions should be logged
     * @return bool The value of {@link logActivities}
     */
    public function shouldLogActivities()
    {
        return $this->logActivities;
    }
    /**
     * Returns the path to the root of where the application has been installed
     * @return string The file path to where the application is installed
     */
    public static function getApplicationRoot()
    {
        return str_replace('src/Inachis/CoreBundle', '', __DIR__);
    }
}
