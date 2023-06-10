<?php
namespace App;
use App\Routing\Router;

class Kernel
{
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

    protected array $routerOptions = [
        "defaultRoutesPath" => "/routes",
    ];

    public function run($root) {
        Router::getInstance()->init($root, $this->routerOptions);
    }

}