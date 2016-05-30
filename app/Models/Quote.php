<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{

  protected $table = 'quotes';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function status( ){
      return $this->hasOne('App\Models\Status');
    }

  protected $fillable = [
        'quote_price', 'user_information',  'appointment_date', 'end_date', 'status_id','quote_source_id', 'quote_inventory_info', 'expire_date', 'user_id', 'appointment_time','user_id','vat','service_tax','labour_charges','created_by','updated_by','id',
  ];

  public function quotes_services() {
      return $this->hasMany('App\Models\ShoppingCartItem');
  }

  public function quotes_materials() {
      return $this->hasMany('App\Models\ShoppingMaterial');
  }
}
