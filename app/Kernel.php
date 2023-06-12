<?php
namespace App;
use App\Contracts\Response;
use App\Exceptions\ResourceNotFoundException;
use App\Routing\Router;

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
        $this->renderResponse($this->handle($root));
    }

    protected function handle($root) {
        try {
            return Router::getInstance()
                ->init($root, $this->routerOptions);
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