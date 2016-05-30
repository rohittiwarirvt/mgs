<?php

namespace App\Http\Controllers;

use JWTAuth;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Input;
use App\Repositories\AdminUserRepository;
use App\Repositories\RolesPermissionRepository;
use App\Http\Controllers\TokenAuthController as TokenAuthController;
use Hash;


use App\Http\Requests;

class AdminUserController extends Controller
{
	public function __construct(AdminUserRepository $AdminUserRepository,RolesPermissionRepository $RolesPermissionRepository) {
      $this->AdminUserRepository = $AdminUserRepository;
      $this->RolesPermissionRepository = $RolesPermissionRepository;
    }

    public function getUsers(Request $request){
    	return $this->AdminUserRepository->getUsers($request);
    }

    public function addUser(Request $request){
    	$request_options = $request->only('username','password','email','phonenumber', 'first_name');
        $rules = [
                'username' => 'required|unique:users',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users',
                'phonenumber' => 'required|numeric|regex:/^[789]\d{9}$/|unique:users',
                'first_name' => 'required'
                ];
        $validator = Validator::make($request_options, $rules);

        if(!$validator->fails()) {
          $credentials = $request_options;
          if (!empty($credentials['password'])) {
            $credentials['password'] = bcrypt($credentials['password']);
            $addUserResult = $this->AdminUserRepository->addUser($credentials);
            if(json_decode($addUserResult)->status == "success"){
            	$userattribute = $request->only('username');
      			$roleattribute = $request->only('selectedRoles');
            $addUserRoleResult = $this->RolesPermissionRepository->assignRole($userattribute, $roleattribute);
            	if($addUserRoleResult->getData()->message == "success"){
            		return json_encode(array("status"=>"success","message"=>"User Created"));	
            	}
            	else{
            		//Todo method to delete user since role was not created.
            		return json_encode(array("status"=>"error","message"=>"Error in creating User"));		
            	}	
            }else{
            	return json_encode(array("status"=>"error","message"=>"Error in Registering User"));
            }
          }
          else {
            return NULL;
          }
        } else {
              return json_encode(array("status"=>"error","message"=>$validator->messages()));
        }
    }


    public function getAdminUser($id)
    {
        $users = User::findOrFail($id);
        return $users;
    }

    public function getAdminUserRole($id){
        $user_role = DB::table('role_user')->where('user_id', $id)
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('roles.display_name')
                        ->get();
        return json_encode($user_role);
    }

    public function updateUser(Request $request, $id)
    {
        $user_details = User::where('id', $id)->first();
        $rules = array(
            'email'      => 'required|email|unique:users,email,'.$id,
            'username'   => 'required|unique:users,username,'.$id,
            'phonenumber' => 'required|unique:users,username,'.$id,
            'first_name' => 'required'

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            $data = array('error_msg' => $messages);
            return json_encode($data);
        }
        else{
            $user_details = User::where('id', $id)->first();
            $updated_user_details = $request->all();
            $user_details->phonenumber = $updated_user_details['phonenumber'];
            $user_details->email = $updated_user_details['email'];
            $user_details->first_name = $updated_user_details['first_name'];
            if(!empty($updated_user_details['password']) && $updated_user_details['password'] == $updated_user_details['confirmPassword']){
              $user_details->password = bcrypt($updated_user_details['password']);
            }
            $user_details->save();
            if(!empty($updated_user_details['selectedRoles'])){
                $userattribute = $request->only('username');
                $roleattribute = $request->only('selectedRoles');
                DB::table('role_user')->where('user_id', $id)->delete();
                $updateUserRoleResult = $this->RolesPermissionRepository->assignRole($userattribute, $roleattribute);
            }
            $data['succ_msg'] = "User Profile has been successfully updated";
            return json_encode($data);
        }
    }

    public function deleteUser($id){
      $users = User::findOrFail($id);
      User::destroy($users->id);
      $data = array('succ_msg' => 'User has been deleted successfully');
      return json_encode($data);

    }
}
?>