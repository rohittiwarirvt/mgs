<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    protected $table = 'status_history';
    protected $fillable = [
        'metadata_id', 'status_id', 'updated_by', 'type'];
}
