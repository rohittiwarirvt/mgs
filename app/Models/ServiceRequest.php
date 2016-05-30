<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_request';
    protected $fillable = [
        'id',
        'user_name', 
        'user_info', 
        'service_info', 
        'appointment_date', 
        'appointment_time', 
        'sr_price', 
        'status_id', 
        'expire_date', 
        'source_id', 
        'technician_info', 
        'progress_status', 
        'payment_status', 
        'created_by', 
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
