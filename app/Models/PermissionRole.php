<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model {
	 protected $table = 'permission_role';

	 public function scopeWhereArray($query, $array) {
        foreach($array as $field => $value) {
            $query->where($field, $value);
        }
        return $query;
    }
}
