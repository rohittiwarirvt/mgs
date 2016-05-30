<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\OptionAndOptionChoiceRepository;
class AttributeRepository {

  private $attribute;
  private $option;

  public function __construct(Attribute $attribute, OptionAndOptionChoiceRepository $option) {
    $this->attribute = $attribute;
    $this->option = $option;
  }


  public function createAttribute($data) {
    return $this->attribute->firstOrCreate($data);

  }

  public function update(array  $data, $id, $attribute = 'id') {
    return $this->attribute->where($attribute, '=', $id)->update($data);
  }

  public function find($id, $columns = array('*')) {
    return $this->attribute->find($id, $columns);
  }

  public function findAttributeBy($matches =['is_active' => 1], $columns = array('*')) {
    return $this->attribute->where($matches)->first($columns);
  }

  public function getAllAttributes($matches = ['is_active'=> 1 ]) {
    return $this->attribute->where($matches)->orderBy('weight', 'asc')->get();
  }


  public function assignOptionToAttribute(array $attribute, array $options) {
     $attribute = $this->findAttributeBy($attribute);

    if ( $attribute && $options) {
      return $this->utilOptionToAttribute($attribute, $options);

    } else {
      return response()->json(['error', 'invalid_operation'], 403);
    }
  }

  public function utilOptionToAttribute($attribute, $options) {
    list($key, $option) = each($options);
    if (is_array($option)){
      foreach ($option as $value) {
        return $this->utilOptionToAttribute($attribute, $value);
      }
    }
    else {
        $option =  $this->option->findOptionBy($key , $option, ['id']);
        if (!$option) {
          $option = $this->option->createOption($options);
        }
        return $attribute->options()->save($option);
    }
  }

  public function showAttributeWithOptions($attribute_name){
      $attributes = $this->findAttributeBy($attribute_name)->options;
      if ($attributes) {
        return $attributes;
      } else {
        return response()->json(['error', 'invalid_operation'], 403);
      }
    }

}
