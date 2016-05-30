<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\OptionAndOptionChoiceRepository;

class OptionAndOptionChoiceController extends Controller
{
    protected $option_and_choice;

    public function __construct(OptionAndOptionChoiceRepository $option_and_choice)
    {
      //  $this->middleware('jwt.auth', ['except' => ['index','getAllAttributes','store']]);
        $this->option_and_choice = $option_and_choice;
    }

    public function createOption(Request $request){
      return $this->option_and_choice->createOption($request->only('option_name', 'option_description', 'weight', 'is_active','price'));

    }

    public function createOptionChoices(Request $request){
      return $this->option_and_choice->createChoice($request->only('choice_name', 'choice_description', 'is_active'));
    }

    public function assignOptionTheChoice(Request $request){
      $option_name = $request->only('option_name');
      $choice_name = $request->only('choice_name');
      return $this->option_and_choice->assignOptionTheChoice($option_name, $choice_name);
    }


    public function showOptionsWithChoices(Request $request){
      $option_name = $request->only('option_name');
      $choice_name = $request->only('choice_name');
      return $this->option_and_choice->showOptionsWithChoices($option_name, $choice_name);
    }

    public function showOptions(Request $request){
      return $this->option_and_choice->getAllOptions();
    }

    public function showChoices(Request $request){
      return $this->option_and_choice->getAllOptionChoices();
    }
}
