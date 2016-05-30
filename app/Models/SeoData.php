<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoData extends Model
{
  protected $table = 'seodata';
  protected $fillable = [
		'webpage_id', ' version', 'title', 'meta_description', 'meta_keyword', 'page_type', 'canonical', 'seocopy_block', 'robots',  'authorship', 'opengraph_type',  'opengraph_title', 'opengraph_description', 'opengraph_url', 'opengraph_image', 'twitter_card',  'twitter_title', 'twitter_description', 'twitter_image', 'twitter_url', 'schema_type', 'schema_name', 'schema_description', ' schema_image', ' updated_by',  'created_by', ' H1',
	];
}
