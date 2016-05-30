<?php

namespace App\Repositories;

use DB;


class AdminUserRepository{

   public function __construct(){
     $this->oldmgsdb = DB::connection('mysql2');
   }

  public function getUsers($request) {
    $users = DB::table('users');
    if(isset($request['role'])) {
    	$users = $users->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', $request['role']);
    }    	
    $users = $users->get();
 
    return $users;
  }

  public function addUser($credentials){
    $max_user_id = $this->oldmgsdb->table('mgs_user')->max('UserId');
    $credentials['id'] = $max_user_id + 1;
  	$result = DB::table('users')->insert($credentials);
  	if($result == 1)
  		return json_encode(array("status"=>"success"));
  }


}

?>