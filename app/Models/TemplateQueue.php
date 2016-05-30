<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateQueue extends Model
{
    protected $table = 'template_queue';
    protected $fillable = ['user_id', 'template_id', 'to', 'from', 'template_data'];
}
