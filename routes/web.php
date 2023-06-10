<?php
use App\Routing\Router;

Router::post("/", [\Controllers\HomeController::class, "indexAction"]);

