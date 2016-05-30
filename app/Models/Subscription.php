<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //

   protected $table = 'subscription';
   protected $fillable = [
        'user_id', 'subscriber_email', 'source', 'token',
    ];
}
