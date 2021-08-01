<?php

namespace App\Http\Request;

use Rakit\Validation\ErrorBag;
use Rakit\Validation\Validator as ValidatorLib;

class Validator
{

    public ValidatorLib $validator;

    public function __construct()
    {
        $this->validator = new ValidatorLib;
    }

    public ErrorBag $errors;

    public array $rules = [];

    public function validate(): bool
    {
        $validation = $this->validator->make($_POST + $_FILES, $this->rules);
        $validation->validate();


        if ($validation->fails()) {
            $this->errors = $validation->errors();
            return false;
        }
        return true;

    }

}