<?php

namespace App\Http\Controller;


abstract class BaseController
{

    private $render;


    public function __construct($render)
    {
        $this->render = $render;
    }

    protected function render($layout, $params = [])
    {
        $render = $this->render;
        return $render($layout, $params);
    }
}
