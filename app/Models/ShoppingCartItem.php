<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    protected $fillable = [
        'product_id', 'quote_id', 'attribute_id', 'option_id','product_value','attribute_value','option_value','quantity', 'discount','unit'
    ];

    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    public function quote() {
      return $this->belongsTo('App\Models\Quote');
    }

    public function product(){
      return $this->belongsTo('App\Models\Product');
    }

    public function attribute(){
      return $this->belongsTo('App\Models\Attribute');
    }

    public function option(){
      return $this->belongsTo('App\Models\Option');
    }

     public function service()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
