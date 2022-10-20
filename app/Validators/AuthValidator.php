<?php

namespace App\Validators;

use App\Validators\AbstractValidator;

/**
 * Class AuthValidator.
 *
 * @package namespace App\Validators;
 */
class AuthValidator extends AbstractValidator
{
    protected $rules = [
        'REGISTER'        => [
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:4'],
        ],
        'LOGIN'        => [
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:4'],
        ],
    ];
}
