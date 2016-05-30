<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingMaterial extends Model
{
   protected $fillable = [
        'quote_id', 'material_name', 'material_quantity', 'unit_price', 'material_total',
    ];

  public function quote() {
      return $this->belongsTo('App\Models\Quote');
  }
}
