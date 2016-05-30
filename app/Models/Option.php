<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{


  public function optionchoices()
    {
        return $this->hasMany('App\Models\Choice');
    }

    protected $fillable = [
        'option_name', 'option_description', 'is_active','price'
    ];

}
