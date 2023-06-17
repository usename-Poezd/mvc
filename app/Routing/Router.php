<?php
namespace App\Routing;

use App\Contracts\Router as RouterBase;
use App\Exceptions\ResourceNotFoundException;
use Exception;

class Router implements RouterBase
{
    private static ?Router $instance = null;

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

    /** @var $routes array<\App\Contracts\Route>*/
    protected array $routes = [];

    public function handle(Request $request): \App\Contracts\Route {

        $route = $this->matchRoute($request);
        if (!isset($route)) {
            throw new ResourceNotFoundException("Resource not found");
        }

        return $route;
    }

    /*
     *  Перебрать занчения мэтча по аргументам, записать в arguments.
     *  далее сравнить кол-во параметров и кол-во мэтчей в ОСНОВНОЙ РЕГУЛЯРКЕ (сравниваем роут с url)
     *  после этого записывать в params[$argument] = $ОСНОВНОЙ МЕТЧ[ключ]
     *  inspire: https://github.com/dannyvankooten/PHP-Router/blob/dfd121cffc435e7d5483c9bfeef0f8ed25afee19/src/Router.php#L117
    */
    protected function matchRoute(Request $request): ?\App\Contracts\Route
    {
        foreach ($this->routes as  $route) {
            // replace first "/" from url
            $reqUrl =  preg_replace("/(^\/)|(\/$)/","",  $request->getUrl());

            $argument_names = $route->getArguments();

            // array of matched params
            $matched = [];
            if (!preg_match($route->getRegex(), $reqUrl, $matched)) {
                continue;
            };
            // delete first element of array because it's url
            array_splice($matched, 0, 1);

            if (count($argument_names) !== count($matched)) {
                continue;
            }

            // params array
            $params = [];
            foreach ($argument_names as $k => $argument) {
                $params[$argument] = $matched[$k];
            }
            $request->setParams($params);

            return $route;
        }

        return null;
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
        $this->routes[] = new Route($method, $route, $action[0], $action[1]);
    }
}