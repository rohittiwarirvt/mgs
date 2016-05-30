<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use JWTAuth;
use Auth;

class PermissionController extends Controller
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
			if(!$user->can('view-permission'))
				return response('Forbidden',403);
		}

		$permissions = Permission::get();
		$permissionarr = array();
		$i=0;
		foreach ($permissions as $permission)
		{
			$permissionarr[$permission['module']][$i]	= $permission;
			$i++;
		}
		return $permissionarr;
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
        $id = $user->id;
		if(!$user->hasRole(config('initializer.Master Admin'))) {
			if(!$user->can('add-permission'))
				return response('Forbidden',403);
		}

		$rules = array(
        'name'        => 'required|unique:permissions|max:255',
        'display_name'    => 'required|max:255',
        'module'    => 'max:255'
    	);

	    $validator = Validator::make($request->all(), $rules);
	    if ($validator->fails()) {

       		// get the error messages from the validator
        	$messages = $validator->messages();

        	// redirect our user back to the form with the errors from the validator
            $data = array('error_msg' => $messages);
            return json_encode($data);

    	} else {
            $permission                   =   new Permission();
            $permission->name             =   $request->input('name');
            $permission->display_name     =   $request->input('display_name');
            $permission->description      =   $request->input('description');
            $permission->module           =   $request->input('module');
            $permission->updated_by       =   $id;
            $permission->created_by       =   $id;
            $permission->save();
            $data['succ_msg']   =   "Permission Added Successfully.";
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
        $permission = Permission::findOrFail($id);
		//$this->authorize($permission);
		return $permission;
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
        'name'        =>    'required|unique:permissions,name,'.$id.'|max:255',
        'display_name'    => 'required|max:255'
        );

        $permission = Permission::where('id',$id)->first();
		//$this->authorize($permission);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            $data = array(
            'error_msg' => $messages);
            return json_encode($data);

        } else {

            if($permission){
                $permission->name             =   $request->input('name');
                $permission->display_name     =   $request->input('display_name');
                $permission->description      =   $request->input('description');
                $permission->module           =   $request->input('module');
                $permission->save();
                $data['succ_msg']   =   "Permission Updated Successfully.";
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
        $permission = Permission::where('id',$id)->first();
		//$this->authorize($permission);
        if($permission){
             Permission::destroy($permission->id);
            return  response('Success',200);;
        }else{
            return response('Unauthoraized',403);
        }
    }
}
