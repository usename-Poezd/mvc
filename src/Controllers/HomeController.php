<?php
namespace Controllers;

use App\Routing\Request;
use App\Routing\Response;

class HomeController
{
    public function indexAction(Request $request)
    {
        return new Response(
            [
                "ok" => true
            ]);
    }
}