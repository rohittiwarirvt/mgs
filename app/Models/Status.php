<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

  public function quotes()
    {
        return $this->hasMany('App\Model\Quote');
    }
}
