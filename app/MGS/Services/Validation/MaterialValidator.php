<?php

namespace MGS\Services\Validation;
use \Prettus\Validator\LaravelValidator;


class MaterialValidator extends LaravelValidator {


  public $rules = array(
         'material_name' => array('required'),
         'material_quantity' => array('required'),
         'unit_price' => array('required'),
         'material_total' => array('required'),
         'quote_id' => array('required'),
      );
}
