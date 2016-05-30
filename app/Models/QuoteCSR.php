<?php

namespace App;
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuoteCSR extends Model
{
    protected $table = 'quote_csr';
    protected $fillable = [
        'user_id', 'quote_id', 'created_by' 
    ];
}
