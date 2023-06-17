<?php
namespace Controllers;

use App\Core\View;
use App\Routing\Request;

class HomeController
{
    public function indexAction(Request $request)
    {
        return new View("home.index", ['test' => "Test string from controller: " . self::class]);
    }
}