<?php

namespace MGS\Services\Validation;
use \Prettus\Validator\LaravelValidator;

class VisitorValidator extends LaravelValidator {


  public $rules = array(
         'customer_id' => array('required'),
         'quote_id' => array('required'),
         'user_id' => array('required'),
      );
}
