<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FileEntry extends Model
{

  protected $table = 'files';

  protected $fillable = ['filemime', 'file_uri',  'file_name', 'file_size' ];

}
