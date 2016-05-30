<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    //

    public function product()
    {
        return $this->belongsToMany('App\Models\Product',  'product_attribute');
    }

  public function options()
    {
        return $this->hasMany('App\Models\Option','attribute_id');
    }
  protected $fillable = [
        'attribute_name', 'attribute_description', 'weight','is_active'
  ];
}

