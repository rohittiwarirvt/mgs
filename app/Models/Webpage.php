<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webpage extends Model
{
    protected $table = 'webpage';
  protected $fillable = [
		'id', 'friendly_url', 'created_by', 'updated_by', 'is_active', 'webpage_id', ' updated_by',  'created_by',
	];
}
