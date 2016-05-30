<?php

namespace MGS\Services\Validation;
use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

class RegistrationValidator extends LaravelValidator {


  public  $rules = [
            ValidatorInterface::RULE_CREATE => [
                'first_name' => 'required',
                'username' => 'required|unique:users|min:3',
                'password' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'phonenumber' => 'required|numeric|unique:users|regex:/^[0789]\d{9}$/'
             ],
            ValidatorInterface::RULE_UPDATE  => [],
             ];
}
