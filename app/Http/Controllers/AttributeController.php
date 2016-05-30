<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\AttributeRepository;

class AttributeController extends Controller
{
    protected $attribute;

    public function __construct(AttributeRepository $attribute)
    {
       // $this->middleware('jwt.auth', ['except' => ['index','getAllAttributes','store']]);
        $this->attribute = $attribute;
    }

    public function index()
    {
        return $this->attribute->getAllAttributes();
    }

    public function store(Request $request)
    {

      return $this->attribute->createAttribute($request->only('attribute_name', 'attribute_description', 'weight', 'is_active'));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }


    public function show($id)
    {
        $attribute = $this->attribute->find($id);
        return $attribute;
    }

    public function assignOptionToAttribute(Request $request){
      $attribute_name = $request->only('attribute_name');
      $option_name = $request->only('option_name');
      return $this->attribute->assignOptionToAttribute($attribute_name, $option_name);
    }

    public function showAttributeWithOption(Request $request){
      $attribute_name = $request->only('attribute_name');
      return $this->attribute->showAttributeWithOptions($attribute_name);
    }
}
