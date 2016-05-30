<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{

    protected $product;

    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        return $this->product->getAllProducts();
    }

    public function store(Request $request)
    {

      return $this->product->createProduct($request->only('product_name', 'product_description','weight'));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }


    public function show($id)
    {
        $user = $this->product->find($id);
        return $user;
    }

    public function assignAttributeToProduct(Request $request){
      $product_name = $request->only('product_name');
      $attribute_name = $request->only('attribute_name');
      return $this->product->assignAttributeToProduct($product_name, $attribute_name);
    }

    public function showProductWithAttribute(Request $request){
      $product_name = $request->only('product_name');
      return $this->product->showProductWithAttribute($product_name);
    }
}
