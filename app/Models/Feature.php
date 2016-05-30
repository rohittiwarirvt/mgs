<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
   protected $table = 'features';
   protected $fillable = ['file_id', 'service_id', 'title', 'description']; 
}
