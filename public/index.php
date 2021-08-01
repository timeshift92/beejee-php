<?php

use App\Http\Controller\AuthController;
use Framework\Features\AuthDecorator;
use Framework\Http\ControllerResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Diactoros\ServerRequestFactory;

use App\Http\Controller\TaskController;
use App\Repositories\BaseRepository;
use App\Repositories\TaskRepository;
use Laminas\Diactoros\ServerRequest;

chdir(dirname(__DIR__));
session_start();

require 'vendor/autoload.php';


$pdo = require 'config/pdo.php';
BaseRepository::$pdo = $pdo;

require 'config/initialize.php';


### Template

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$twig->addGlobal('session', new \Framework\Http\Session\SessionFlash());
$twig->addGlobal('auth', new \Framework\Http\Session\SessionAuth());

$render = function ($layout, $params) use ($twig) {
    return $twig->render($layout . ".html.twig", $params);
};

## Router

$aura = new Aura\Router\RouterContainer('/public');
$routes = $aura->getMap();


$routes->get('task', '/', [new TaskController($render), 'index']);
$routes->post('task-add', '/', [new TaskController($render), 'store']);
$routes->post('login', '/login', [new AuthController($render), 'login']);
$routes->post('logout', '/logout', new AuthDecorator([new AuthController($render), 'logout']));

$routes->post('task-update', '/{id}', new AuthDecorator([new TaskController($render), 'update']));
$routes->post('task-complete', '/{id}/complete', new AuthDecorator([new TaskController($render), 'complete']));
$routes->post('task-un-complete', '/{id}/un-complete', new AuthDecorator([new TaskController($render), 'unComplete']));


$router = new AuraRouterAdapter($aura);
\App\Http\Request\Path::$router = $router;
$resolver = new ControllerResolver();
$twig->addExtension(new \Framework\Template\Twig\Extension\RouteExtension($router));
### Running

$request = ServerRequestFactory::fromGlobals();


$twig->addGlobal('params', $request->getQueryParams());
try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new HtmlResponse('Undefined page', 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'Timeshift');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
