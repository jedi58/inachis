<?php
namespace Inachis\Component\CoreBundle\Routing;

use Inachis\Component\CoreBundle\Exception\RouteConfigException;

/**
 * Class used for describing a route
 */
class Route
{
    /**
     * @var string[] The HTTP method(s) the route is for
     */
    protected $methods;
    /**
     * @var string The route being handled
     */
    protected $path;
    /**
     * @var string The name of the function the route should respond with
     */
    protected $action;
    /**
     * The default constructor for {@link Route}
     * @var string[] $methods The HTTP methods handled by the route
     * @var string $path The path the route is for
     * @var string $action The function name to use when route followed
     */
    public function __construct($methods = array('GET'), $path = '', $action = null)
    {
        $this->methods = $methods;
        $this->path = $path;
        $this->action = $action;
    }
    /**
     * Returns an instance of {@link Route}
     * @return Route The current or a new instance of {@link Route}
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * Hydrates the current object with values from a {@link stdClass} and also returns itself
     * @param stdClass $values The simple object containing values to assign to current {@link Route}
     * @return Route The hyrdated route
     */
    public function hydrate($values)
    {
        if (!is_object($values)) {
            throw new RouteConfigException('Route could not be parsed: ' . print_r($values, true) . PHP_EOL);
        }
        $this->setMethods($values->methods);
        $this->setPath($values->path);
        $this->setAction($values->action);
        return $this;
    }
    /**
     * Returns the supported HTTP methods for the {@link Route}
     * @return string[] The array of supported HTTP methods for this route
     */
    public function getMethods()
    {
        if (empty($this->methods)) {
            $this->setDefaultMethods();
        }
        return $this->methods;
    }
    /**
     * Returns the path for the route
     * @return string The path for the route
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * The name of the function to use for this route
     * @return string The name of the function to call for the route
     */
    public function getAction()
    {
        return $this->action;
    }
    /**
     * Assigns HTTP methods for the current route
     * @param string|string[] The array of supported HTTP methods
     * @throws RouteConfigException
     */
    public function setMethods($value)
    {
        if (empty($value) || (is_array($value) && empty($value[0]))) {
            throw new RouteConfigException('Routing methods must be an array containing valid HTTP/1.1 methods');
        }
        $this->methods = $value;
    }
    /**
     * Assigns the path to handle for the current route
     * @param string $value The path to be handled
     * @throws RouteConfigException
     */
    public function setPath($value)
    {
        if (empty($value)) {
            throw new RouteConfigException('Path for routing cannot be empty');
        }
        $this->path = $value;
    }
    /**
     * Assigns the action for the current route
     * @param string $value The method name of the action to use
     * @throws RouteConfigException
     */
    public function setAction($value)
    {
        $action = explode('::', $value);
        if (empty($action[1]) || !method_exists($action[0], $action[1])) {
            throw new RouteConfigException('Invalid function name for route. ' . PHP_EOL . $this->formatRoute());
        }
        $this->action = $value;
    }
    /**
     * Sets the default HTTP methods for the current route
     */
    public function setDefaultMethods()
    {
        $this->setMethods(array('GET'));
    }
    /**
     * Returns the current route as a formatted string
     * @return string The formatted output of the current route
     */
    public function formatRoute()
    {
        return
            'Methods: ' . implode(',', $this->getMethods()) . PHP_EOL .
            'Path:    ' . $this->getPath() . PHP_EOL .
            'Action:  ' . $this->getAction() . PHP_EOL;
    }
}
