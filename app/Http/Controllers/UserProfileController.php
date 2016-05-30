<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\FileRepository;
use App\Models\FileEntry;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use Hash;
use JWTAuth;
use Crypt;



class UserProfileController extends Controller
{
    protected $tasks;
    protected $file;

    public function __construct(UserRepository $user, FileRepository $file)
    {
        $this->middleware('jwt.auth', ['except' => ['index','getAllUser']]);

        $this->tasks = $user;
         $this->file = $file;
    }

    public function index()
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
         $rules = array(

            'email'      => 'required|email|unique:users,email,'.$user->id,
            'username'   => 'required|unique:users,username,'.$user->id,
            'firstname'  =>  'required',
            'lastname'   =>  'required',
            'phonenumber' => 'required',

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
            $user = JWTAuth::parseToken()->authenticate();
            $user_details = User::where('id', $user->id)->first();
            $user_details->username = $request->input('username');
            $user_details->first_name = $request->input('firstname');
            $user_details->last_name = $request->input('lastname');
            $user_details->email = $request->input('email');
            $user_details->address1 = $request->input('address1');
            $user_details->address2 = $request->input('address2');
            $user_details->city = $request->input('city');
            $user_details->pincode = $request->input('pincode');
            $user_details->phonenumber = $request->input('phonenumber');
            $user_details->updated_by = $user->id;
            $user_details->save();
            $data['succ_msg'] = "Your Profile has been successfully updated";
            $data['user'] = $user_details;
            return json_encode($data);
        }
    }

    public function destroy($id)
    {

    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $details = User::where('id', $user->id)->first();
        return $details;
    }

    public function resetPassword(Request $request){
        $rules = array(

            'cpassword'   => 'required',
            'npassword'   => 'required',

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
            $password_details = $request->all();
            $user = JWTAuth::parseToken()->authenticate();
            $user_details = User::where('id', $user->id)->first();
            if (Hash::check($password_details['cpassword'], $user_details['password']) && $password_details['npassword'] == $password_details['confirmPassword'])
            {
                $new_password = bcrypt($password_details['npassword']);
                $user_details->password = $new_password;
                $user_details->pass_copy = base64_encode($password_details['npassword']);
                $user_details->save();
                $data['succ_msg'] = "Your password has been changed successfully";
                return json_encode($data);
            }
        }

    }

}

