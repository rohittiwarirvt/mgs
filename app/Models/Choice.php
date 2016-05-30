<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{

  public function options()
    {
        return $this->belongsTo('App\Models\Option');
    }

    protected $fillable = [
        'choice_name', 'choice_description', 'is_active', 'weight'
  ];

}
