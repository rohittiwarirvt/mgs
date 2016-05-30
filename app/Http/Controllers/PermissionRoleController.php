<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\PermissionRole;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use JWTAuth;
use Auth;

class PermissionRoleController extends Controller
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

		if(!$user->hasRole(config('initializer.Master Admin')))
		{
			if(!$user->can('view-permission'))
				return response('Forbidden',403);
		}
		$permissions    =   Permission::get();
        $roles          =   Role::get();
        $permissionrole =   PermissionRole::get();
        $newarray       =   array();
        foreach ($permissionrole as $pr) {
            $newarray[] =   $pr->permission_id."_".$pr->role_id;
        }

        $permissionroles =   array('permissions'=>$permissions, 'roles'=>$roles, 'permissionrole1'=>$newarray);
        return $permissionroles;
    }
    public function update(Request $request)
    {
        $user = Auth::user();
		if(!$user->hasRole(config('initializer.Master Admin')))
		{
			if(!$user->can('assign-permission'))
				return response('Forbidden',403);
		}

		$params      =   $request->all();
        $permission  =   Permission::where('id',$params['permissionid'])->first();
        $roles       =   Role::where('id',$params['roleid'])->first();
        if($params['checkStatus']==true)
        {
            $roles->attachPermission($permission);
        }
        else
        {
           $permission_role = PermissionRole::where('permission_id','=',$params['permissionid'])->where('role_id','=',$params['roleid'])->get();

           if($permission_role){
                $whereArray = array('permission_id' => $params['permissionid'],'role_id' => $params['roleid']);
                PermissionRole::whereArray($whereArray)->delete();
                return  response('Success',200);;
            }else{
                return response('Unauthoraized',403);
            }
        }
    }
}
