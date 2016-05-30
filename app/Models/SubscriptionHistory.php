<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    protected $table = 'subscription_history';
    protected $fillable = ['subscription_id', 'subscriber_email', 'source'];
}
