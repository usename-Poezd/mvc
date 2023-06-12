<?php
namespace App\Routing;

use App\Contracts\Router as RouterBase;
use App\Exceptions\ResourceNotFoundException;
use Exception;

class Router implements RouterBase
{
    private static ?Router $instance = null;
    protected array $routes = [];

    public static function getInstance(): Router
    {
        if (self::$instance === null) {

            self::$instance = new self();

        }
        return self::$instance;
    }

    private function __construct(){}
    private function __clone(){}
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public function init(string $root, array $options): Response
    {
        $this->initRoutes($root . $options["defaultRoutesPath"]);


        return $this->handle(Request::get());
    }

    protected function initRoutes($path)
    {
        $files = $this->getRoutingFiles($path);
        foreach ($files as $file) {
            require_once $path . $file;
        }
    }
    protected function getRoutingFiles($path): array
    {
        $fileNames = [];
        $d = dir( $path) or die($php_errormsg);
        while (false !== ($f = $d->read())) {
            if (preg_match('/^[a-zA-Z]+.php$/',$f)) {
                $fileNames[] = "/".$f;
            }
        }
        $d->close();
        return $fileNames;
    }

    protected function handle(Request $request): Response {
        $route = $this->routes[$request->getUrl()][$request->getMethod()] ?? null;
        if (!isset($route)) {
            throw new ResourceNotFoundException("Resource not found");
        }

        $controller = $route["controller"];
        $action = $route["action"];

        return (new $controller())->$action($request);
    }


    public static function get(string $route, array $action)
    {
        self::getInstance()->register("GET", $route, $action);
    }

    public static function post(string $route, array $action)
    {
        self::getInstance()->register("POST", $route, $action);
    }

    protected function register($method, $route, $action)
    {
        $this->routes[$route][$method] = [
            "controller" => $action[0],
            "action"    => $action[1],
        ];
    }
}