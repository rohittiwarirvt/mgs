<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    protected $table = 'address_book';
    protected $fillable = [
        'uid', 'title', 'first_name', 'last_name', 'address_line1', 'address_line2', 'landmark', 'pincode', 'country_id', 'state_id', 'city_id', 'phone_number', 'email', 'created_by', 'updated_by'
    ];
}
