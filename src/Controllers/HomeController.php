<?php
namespace Controllers;

use App\Routing\Request;

class HomeController
{
    public function indexAction(Request $request)
    {
        echo $request->getUrl();
    }
}