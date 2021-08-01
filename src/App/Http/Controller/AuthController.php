<?php

namespace App\Http\Controller;

use App\Http\Request\Path;
use App\Repositories\TaskRepository;
use Framework\Http\Session\SessionFlash;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;

class AuthController extends BaseController
{
    public TaskRepository $repository;

    public function __construct($render)
    {
        parent::__construct($render);
        $this->repository = new TaskRepository();
    }

    public function login(ServerRequest $request): RedirectResponse
    {
        if (isset($request->getParsedBody()['login']) && isset($request->getParsedBody()['password'])) {
            $login = $request->getParsedBody()['login'];
            $password = $request->getParsedBody()['password'];
            if ($login == 'admin' && $password = "1234") {
                $_SESSION["login"] = $request->getParsedBody()['login'];
            } else {
                SessionFlash::error("Невереные данные");
            }
        }

        return new RedirectResponse(Path::generate('task'));
    }

    public function logout()
    {
        session_destroy();
        if (isset($_SESSION["login"]))
            unset($_SESSION["login"]);
        return new RedirectResponse(Path::generate('task'));
    }
}