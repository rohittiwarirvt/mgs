<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use Hash;
use App\Repositories\UserRepository;
use App\Repositories\RolesPermissionRepository;
use App\Repositories\UserMigrationRepository;
use App\Models\Role;
use App\Http\Controllers\OtpVerificationController;
use Larapi;


class TokenAuthController extends Controller
{

    public function __construct(UserRepository $tasks, RolesPermissionRepository $roles,UserMigrationRepository $usermigration){
      $this->middleware('jwt.auth', ['except' => ['authenticate', 'register', 'checkIfUserExists', 'createVisitor', 'setUserPassword']]);
      $this->tasks = $tasks;
      $this->roles = $roles;
      $this->usermigration = $usermigration;
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $rules = array(
            'username'      => 'required|min:3',
            'password'       => 'required|min:5'
        );
        $validator = Validator::make($credentials, $rules);
        if (!$validator->fails()) {
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Please enter correct username and password.'], 401);
                }
                else {
                    $user = $this->tasks->findUserBy($credentials);
                    $phonenumber = $user['phonenumber'];
                    $id = $user['id'];

                    $credentials['status'] = 1;
                    if(! $token = JWTAuth::attempt($credentials)) {
                      $error_message = 'Your mobile number is not verified.';

                      return response()->json(['error' => $error_message, 'phonenumber'=> $phonenumber, 'id'=>$id], 401);
                    }
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }




    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
      foreach( $user->roles as $key=>$role )  {
            $permissions  = Role::with('perms')->where('id', $role->id)->get();

            $arr=array();
            $i=0;
            foreach($permissions[0]['perms'] as $permission)
            {
              $arr[$i] = $permission->name;
              $i++;
            }

            $user->roles[$role->name]=$arr;

            if (is_numeric($key))
              unset($user->roles[$key]);
          }

        return response()->json(compact('user'));
    }

    public function register(Request $request) {
      $request_options = $request->only('username', 'password', 'email', 'phonenumber', 'first_name', 'user_type', 'customer_id','last_name', 'source','status');
      $not_send_otp = $request_options['source'];
      unset($request_options['source']);
      $request_options = array_filter($request_options);
      $credentials = $request_options;

      if (!empty($credentials['password'])) {
           $credentials['pass_copy'] = base64_encode($credentials['password']);
           $credentials['password'] = bcrypt($credentials['password']);
           $user_role  = array();
           $user = $this->tasks->createUser($credentials);
           if($user->getData()->message == "error")
            return $user;
           $user_role['selectedRoles'] = array('Individual Customer');
           unset($request_options['password']);
           $roles = $this->roles->assignRole($request_options, $user_role);
           $user_migration = $this->usermigration->migrateUserToOldApp($credentials);

           //generate otp
           if(!empty($user->getData()->response->id) && empty($not_send_otp)){
               $request->replace(array('phonenumber' => $request_options['phonenumber']));
               $optVerification = new OtpVerificationController($this->tasks);
               $optVerification->store($request);
           }
           return $user;
      }
      else {
           return NULL;
      }
    }

    public function checkIfUserExists(Request $request) {
      $findParams = $request->only('email','phonenumber','username');
      return $this->tasks->checkIfUserExists($findParams);
    }

    public function createVisitor(Request $request) {
      $visitor = $request->only('customer_id','quote_id','user_id');
      return $this->tasks->createVisitor($visitor);
    }
    public function setUserPassword(Request $request) {
      $data  = $request->only('user_id','password');
      return $this->tasks->setUserPassword($data);
    }

    public function getAuthUserRolesWithPermission(Request $request) {
      return $this->roles->getUserRolesAndPerms();
    }
}

