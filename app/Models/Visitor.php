<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{

    protected $fillable = [
        'customer_id', 'quote_id', 'user_id'
    ];

    public function user() {
        $this->belongsTo('App\Models\User');
    }

        public function quote() {
        $this->belongsTo('App\Models\Quote');
    }
}
