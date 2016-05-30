<?php

namespace MGS\Services\Validation;
use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

class QuoteValidator extends LaravelValidator {


  public $rules = [
            ValidatorInterface::RULE_CREATE => [
              'user_id' => 'required',
              'user_information' => 'required',
              'appointment_date' => 'required',
              'appointment_time' => 'required',
              'status_id' => 'required',
              'quote_source_id' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE  => [],
      ];
}
