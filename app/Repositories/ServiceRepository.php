<?php

namespace App\Repositories;

use App\Repositories\ProductRepository;
use App\Models\Service;

class ServiceRepository {

  private $service;
  private $product;

  public function __construct(ProductRepository $product, Service $service) {
    $this->service = $service;
    $this->product = $product;
  }

  public function createService($data) {
    return $this->service->create($data);
  }

  public function update(array  $data, $id, $attribute = 'id') {
    return $this->service->where($attribute, '=', $id)->update($data);
  }

  public function find($id, $columns = array('*')) {
    return $this->service->find($id, $columns);
  }

  public function findServiceBy($matches = ['is_active'=> 1 ], $columns = array('*')) {
    return $this->service->where($matches)->first($columns);
  }

  public function getAllServices($matches = ['is_active'=> 1 ]) {
    return $this->service->where($matches)->with('thumbnails')->orderBy('weight', 'asc')->get();
  }


  public function assignProductToService(array $service, array $products) {
    $service = $this->findServiceBy($service);

    if ( $service && $products) {
      return $this->utilProductToService($service, $products);

    } else {
      return response()->json(['error', 'invalid_operation'], 403);
    }
  }

  public function utilProductToService($service, $products) {
    list($key, $product) = each($products);
    if (is_array($product)){
      foreach ($product as $value) {
        return $this->utilProductToService($service, $value);
      }
    }
    else {
        $product =  $this->product->findProductBy($products, ['id']);
        if (!$product) {
          $product = $this->product->createProduct($products);
        }
        return $service->products()->save($product);
    }
  }

  public function showServiceWithProduct($service){
        $service = array_filter($service);
      $service = $this->findServiceBy($service)->products;
      if ($service) {
        return $service;
      } else {
        return response()->json(['error', 'invalid_operation'], 403);
      }
    }

}
