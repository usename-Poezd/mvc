<?php
namespace App;
use App\Contracts\Response;
use App\Exceptions\ResourceNotFoundException;
use App\Routing\Request;
use App\Routing\Router;
use Exception;

class Kernel
{
    protected array $routerOptions = [
        "defaultRoutesPath" => "/routes",
    ];

    private static ?Kernel $instance = null;

    public static function getInstance(): Kernel
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

    public function run($root) {
        $this->initRoutes($root . $this->routerOptions["defaultRoutesPath"]);
        $this->renderResponse(
            $this->handle(Request::get())
        );
    }

    protected function initRoutes($path): void
    {
        $files = $this->getRoutingFiles($path);
        foreach ($files as $file) {
            require_once $path . $file;
        }
    }

    protected function getRoutingFiles($path): array
    {
        $fileNames = [];
        $d = dir($path) or die($php_errormsg);
        while (false !== ($f = $d->read())) {
            if (preg_match('/^[a-zA-Z]+.php$/',$f)) {
                $fileNames[] = "/".$f;
            }
        }
        $d->close();
        return $fileNames;
    }

    protected function handle(Request $request): Response {
        try {
            $route = Router::getInstance()
                ->handle($request);

            $controller = $route->getController();
            $action = $route->getAction();

            return (new $controller())->$action($request);
        } catch (ResourceNotFoundException $e) {
            return new \App\Routing\Response([
                "ok" => false,
                "message" => "Resource not found",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "trace" => [],
            ], 404);
        } catch (\Throwable $e) {
            return new \App\Routing\Response([
                "ok" => false,
                "message" => "Error occurred",
                "code" => $e->getCode(),
                "error" => $e->getMessage(),
                "trace" => $e->getTrace(),
            ], 500);
        }
    }

    protected function renderResponse(Response $response) {
        http_response_code($response->getStatus());
        foreach ($response->getOptions() as $name => $value) {
            header($name . ": " . $value);
        }
        echo $response->getBody();
    }

}