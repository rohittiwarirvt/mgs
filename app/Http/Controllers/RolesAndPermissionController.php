<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\RolesPermissionRepository;


class RolesAndPermissionController extends Controller
{

    protected $tasks;

    public function __construct(RolesPermissionRepository $tasks) {
      $this->tasks = $tasks;
    }

    public function createRole(Request $request){
      return $this->tasks->createRole($request->only('name'));

    }

    public function getAllRoles(Request $request){
      return $this->tasks->getAllRoles();
    }

    public function createPermission(Request $request){
      return $this->tasks->createPermission($request->only('name'));
    }

    public function assignRole(Request $request){
      $userattribute = $request->only('username');
      $roleattribute = $request->only('name');
      return $this->tasks->assignRole($userattribute, $roleattribute);
    }

    public function attachPermission(Request $request){
        $roleattribute = $request->only('role');
        $permattribute = $request->only('name');
        return $this->tasks->attachPermission($roleattribute, $permattribute);
    }

}
