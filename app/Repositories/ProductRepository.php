<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Service;
use App\Repositories\AttributeRepository;

class ProductRepository {

  private $product;
  private $attribute;

  public function __construct(Product $product, AttributeRepository $attribute) {
    $this->product = $product;
    $this->attribute = $attribute;
  }

  public function createProduct($data) {
    return $this->product->firstOrCreate($data);
  }

  public function update(array  $data, $id, $attribute = 'id') {
    return $this->product->where($attribute, '=', $id)->update($data);
  }

  public function find($id, $columns = array('*')) {
    return $this->product->find($id, $columns);
  }

  public function findProductBy($matches = ['is_active'=> 1 ], $columns = array('*')){
    return $this->product->where($matches)->first($columns);
  }

  public function getAllProducts($matches = ['is_active'=> 1 ]) {
    return $this->product->where($matches)->orderBy('weight', 'asc')->get();
  }


  public function assignAttributeToProduct(array $product, array $attributes) {
    $product = $this->findProductBy($product, ['id']);
    if ( $product && $attributes) {
      return $this->utilAttributeToProduct($product, $attributes);

    } else {
      return response()->json(['error', 'invalid_operation'], 403);
    }
  }

  public function utilAttributeToProduct($product, $attributes) {
    list($key, $attribute) = each($attributes);
    if (is_array($attribute)){
      foreach ($attribute as $value) {
        return $this->utilAttributeToProduct($product, $value);
      }
    }
    else {
        $attribute =  $this->attribute->findAttributeBy($attributes);
        if (!$attribute) {
          $attribute = $this->attribute->createAttribute($attributes);
        }
        return $product->attributes()->save($attribute);
    }
  }

  public function showProductWithAttribute($product){
      $product = $this->findProductBy($product)->attributes;
      if ($product) {
        return $product;
      } else {
        return response()->json(['error', 'invalid_operation'], 403);
      }

    }
}
