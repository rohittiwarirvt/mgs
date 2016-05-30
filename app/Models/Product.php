<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


  public function services()
    {
        return $this->belongsToMany('App\Models\Service', 'service_product');
    }

  public function attributes() {
        return $this->belongsToMany('App\Models\Attribute', 'product_attribute', 'product_id', 'attribute_id');
  }

  protected $fillable = [
        'product_name', 'product_description', 'weight','is_active'
  ];

}
