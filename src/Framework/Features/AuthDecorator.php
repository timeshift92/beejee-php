<?php

namespace Framework\Features;

use Framework\Http\Session\SessionFlash;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class AuthDecorator
{
    private $next;

    public function __construct(callable $next)
    {
        $this->next = $next;
    }

    public function __invoke(ServerRequestInterface $request): RedirectResponse
    {
        if (isset($_SESSION['login'])) {
            return ($this->next)($request);
        }
        SessionFlash::error("Для данного действия необходимо авторизация");

        return new RedirectResponse("/");
    }
}