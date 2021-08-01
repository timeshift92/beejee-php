<?php

namespace Framework\Http\Session;

class SessionAuth
{
    public function user()
    {
        if (isset($_SESSION['login']))
            return $_SESSION['login'];
        return false;
    }
}