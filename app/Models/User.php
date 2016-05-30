<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Profile;
use App\Models\Role;


use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{

     use  CanResetPassword, EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'username', 'email', 'password', 'phonenumber', 'customer_id', 'first_name', 'last_name','customer_id', 'user_type', 'created_by',  'updated_by', 'pass_copy','id','status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_code'
    ];

    public function quotes() {
        return $this->hasMany('App\Models\Quote');
    }

    public function visitor() {
        return $this->hasMany('App\Models\Visitor');
    }
}
