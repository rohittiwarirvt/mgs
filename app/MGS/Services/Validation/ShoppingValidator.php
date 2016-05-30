<?php

namespace MGS\Services\Validation;
use \Prettus\Validator\LaravelValidator;

class ShoppingValidator extends LaravelValidator {


  public $rules = array(
         'product_id' => array('required'),
         'quote_id' => array('required'),
      );
}
