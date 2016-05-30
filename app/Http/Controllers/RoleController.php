<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use JWTAuth;
use Auth;
use App\Repositories\RolesPermissionRepository;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['']]);
    }

    public function index()
    {   
        $user = Auth::user();
		if(!$user->hasRole(config('initializer.Master Admin'))) {
			if(!$user->can('list-role'))
				return response('Forbidden',403);
		}
		return Role::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
		if(!$user->hasRole(config('initializer.Master Admin'))) {
			if(!$user->can('add-role'))
				return response('Forbidden',403);
		}

		$rules = array(
        'name'        => 'required|unique:roles|max:255',
        'display_name'    => 'required|max:255'
    	);
	    $validator = Validator::make($request->all(), $rules);
	    if ($validator->fails()) {

       		// get the error messages from the validator
        	$messages = $validator->messages();

        	// redirect our user back to the form with the errors from the validator
            $data = array('error_msg' => $messages);
            return json_encode($data);

    	} else {

            $role                   =   new Role();
            $role->name             =   $request->input('name');
            $role->display_name     =   $request->input('display_name');
            $role->description      =   $request->input('description');
            $role->save();
            $data['succ_msg']   =   "Role Added Successfully.";
            return $data;
	    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
	public function show($id)
    {
        $role = Role::findOrFail($id);

		//$this->authorize($role);

		return $role;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
        'name'        =>    'required|unique:roles,name,'.$id.'|max:255',
        'display_name'    => 'required|max:255'
        );

        $role = Role::where('id',$id)->first();
		//$this->authorize($role);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            $data = array(
            'error_msg' => $messages);
            return json_encode($data);

        } else {

            if($role){
                $role->name             =   $request->input('name');
                $role->display_name     =   $request->input('display_name');
                $role->description      =   $request->input('description');
                $role->save();
                $data['succ_msg']   =   "Role Updated Successfully.";
                return $data;
            }else{
                return response('Unauthoraized',401);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $roles = Role::where('id',$id)->first();
		//$this->authorize($roles);
        if($roles){
            DB::table('roles')->where('id', '=', $id)->delete();
            return  response('Success',200);;
        }else{
            return response('Unauthoraized',403);
        }
    }
}
