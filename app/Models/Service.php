<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //

  public function products()
    {
        return $this->belongsToMany('App\Models\Product',  'service_product','service_id', 'product_id' );
    }

  protected $fillable = [
        'service_name', 'service_description', 'weight', 'is_active', 'url','file_id','banner_id', 'tag_line',
  ];

  public function thumbnails(){
    return $this->belongsTo('App\Models\FileEntry', 'file_id');
  }
}
