<?php
use App\Routing\Router;

Router::get("/", [\Controllers\HomeController::class, "indexAction"]);

