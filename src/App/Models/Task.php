<?php

namespace App\Models;


use Framework\Features\FromArray;

class Task
{
    use FromArray;

    public $id = 0;
    public $username = null;
    public $email = null;
    public $description = null;
    public $is_complete = null;
    public $created_at = null;

}