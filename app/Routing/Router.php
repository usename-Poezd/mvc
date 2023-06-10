<?php
namespace App\Routing;

use App\Contracts\Router as RouterBase;
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


    protected function getRoutingFiles(string $root, string $dir): array
    {
        $fileNames = [];
        $d = dir( $root . $dir) or die($php_errormsg);
        while (false !== ($f = $d->read())) {
            if (preg_match('/^[a-zA-Z]+.php$/',$f)) {
                $fileNames[] = "/".$f;
            }
        }
        $d->close();
        return $fileNames;
    }

    public function init(string $root, array $options)
    {
        $files = $this->getRoutingFiles($root, $options["defaultRoutesPath"]);
        foreach ($files as $file) {
            require_once $root . $options["defaultRoutesPath"] . $file;
        }


        $this->handle(Request::get());
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

    protected function handle(Request $request) {
        $route = $this->routes[$request->getUrl()][$request->getMethod()] ?? null;
        if (!isset($route)) {
            throw new Exception("PIZDEC 404");
        }

        $controller = $route["controller"];
        $action = $route["action"];

        (new $controller())->$action($request);
    }
}