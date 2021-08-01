<?php

namespace App\Http\Request;

use Framework\Http\Router\AuraRouterAdapter;

class Path
{
    public static AuraRouterAdapter $router;

    public static function generate($name, $params = []): string
    {
        return self::$router->generate($name, $params);
    }

}