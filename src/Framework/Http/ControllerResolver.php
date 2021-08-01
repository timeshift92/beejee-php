<?php

namespace Framework\Http;

class ControllerResolver
{
    public function resolve($handler): callable
    {
        return \is_string($handler) ? new $handler() : $handler;
    }
}
