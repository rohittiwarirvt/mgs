<?php

namespace App\Repositories;

use App\Models\Option;
use App\Models\Choice;

class OptionAndOptionChoiceRepository {

  private $attribute;
  private $choice;

  public function __construct(Option $option, Choice $choice) {
    $this->option = $option;
    $this->choice = $choice;
  }

  public function createOption(array $data) {
    return $this->option->firstOrCreate($data);
  }

  public function updateOption(array $data, $id, $attribute="id") {
    return $this->option->where($attribute, '=', $id)->update($data);
  }

  public function deleteOption($id) {
     return $this->option->destroy($id);
  }


  public function findOption($id, $columns = array('*')) {
    return $this->option->find($id, $columns);
  }


  public function findOptionBy($matches =['is_active' => 1], $value, $columns = array('*')) {
    return $this->option->where($matches)->first($columns);
  }

  public function createChoice($data) {
    return $this->choice->create($data);
  }

  public function updateChoice(array $data, $id, $attribute="id") {
    return $this->choice->where($attribute, '=', $id)->update($data);
  }

  public function deleteChoice($id) {
    return $this->choice->destroy($id);
  }

  public function findChoice($id, $columns = array('*')) {
    return $this->choice->find($id, $columns);
  }

  public function findChoiceBy($matches =['is_active' => 1], $value, $columns = array('*')) {
    return $this->choice->where($matches)->first($columns);
  }



  public function assignOptionTheChoice(array $option, array $choices) {
    $option = $this->findOptionBy($option ['id']);

    if ( $option && $choices) {
      return $this->utilOptionToChoice($option, $choices);

    } else {
      return response()->json(['error', 'invalid_operation'], 403);
    }
  }

  public function utilOptionToChoice($option, $choices) {
    list($key, $choice) = each($choices);
    if (is_array($choice)){
      foreach ($choice as $value) {
        return $this->utilOptionToChoice($option, $value);
      }
    }
    else {
        $choice =  $this->findChoiceBy($choices, ['id']);
        if (!$choice) {
          $choice = $this->createChoice($choice);
        }
        return $option->optionchoices()->save($choice);
    }
  }

  public function showOptionsWithChoices(Request $request){
      $option = $request->only('option_name');
      $option = $this->findOptionBy($option)->optionchoices();
      if ($option) {
        return $option;
      } else {
        return response()->json(['error', 'invalid_operation'], 403);
      }

    }

  public function getAllOptions($matches = ['is_active'=> 1 ]) {
    return $this->option->where($matches)->orderBy('weight', 'asc')->get();
  }

  public function getAllOptionChoices($matches) {
    return $this->choice->where($matches)->get();
  }
}
