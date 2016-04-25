<?php
namespace Inachis\Component\Common\Routing;

use Klein\Klein;
use Inachis\Component\Common\Routing\Route;
use Inachis\Component\Common\Configuration\ConfigManager;

class RoutingManager
{
    /**
     * @var RoutingManager The instance of {@link RoutingManager}
     */
    protected static $instance;
    /**
     * @var Klein\Klein Instance of {@link Klein\Klein} used for routing
     */
    protected $klein;
    /**
     * Default constructor sets up instance of Klein\Klein and
     * adds default routes
     */
    public function __construct()
    {
        $this->klein = new Klein();
        $this->addErrorHandlers();
    }
    /**
     * Returns an instance of {@link RoutingManager}
     * @return RoutingManager The current or a new instance of {@link RoutingManager}
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * Load additional routing configuration using {@link ConfigManager} from the
     * config/routing/ folder. All JSON files in this folder will be parsed
     */
    public function load()
    {
        $routes = ConfigManager::loadAllFromLocation('routing', 'json');
        foreach ($routes as $routeNamespace) {
            if (empty($routeNamespace)) {
                throw new \Exception('No route namespaces defined; please check the application config.');
            }
            foreach ($routeNamespace as $route) {
                $importedRoute = new Route();
                $this->addRoute($importedRoute->hydrate($route));
            }
        }
    }
    /**
     * Adds a route to {@link Klein\Klein} from the details specified in the provided {@link Route}
     * @param Route $route The route details containing methods, path, and action
     */
    public function addRoute(Route $route)
    {
        $this->klein->respond(
            $route->getMethods(),
            $route->getPath(),
            $route->getAction()
        );
    }
    /**
     * Tells {@link Klein\Klein} to handle the current request
     */
    public function dispatch()
    {
        $this->klein->dispatch();
    }
    /**
     * Adds default responder routes for error handling and standard admin interface pages
     */
    private function addErrorHandlers()
    {
        $router = $this->klein;
        $router->onHttpError(function ($code, $router) {
            if ($code >= 400 && $code < 500) {
                // @todo replace with templated error page
                $router->response()->body(
                    'Nothing to see here, you may go about your business. Error: ' . $code
                );
                //$router->response->file(__DIR__ . '404.html');
            } elseif ($code >= 500 && $code <= 599) {
                // @todo replace with templated error page
                $router->response()->body(
                    'Something went a bit wrong. Error: ' . $code
                );
            }
        });
    }
}
