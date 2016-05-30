<?php

namespace App\Repositories;

use App\Repositories\UserRepository;
use App\Models\Role;
use App\Models\Permission;
use Auth;
use Larapi;

class RolesPermissionRepository
{

  protected $user;
  protected $role;
  protected $permission;

  public function __construct(UserRepository $user_rep, Role $role, Permission $permission) {
    $this->user_rep = $user_rep;
    $this->role = $role;
    $this->permission = $permission;
  }

  public function createRole(array $data) {
    return $this->role->create($data);
  }

  public function updateRole(array $data, $id, $attribute="id") {
    return $this->role->where($attribute, '=', $id)->update($data);
  }

  public function deleteRole($id) {
     return $this->role->destroy($id);
  }

  public function getAllRoles(){
      return $this->role->all();
  }


  public function findRole($id, $columns = array('*')) {
    return $this->role->find($id, $columns);
  }


  public function findRoleBy($attribute, $value, $columns = array('*')) {
    return $this->role->where($attribute, '=', $value)->first($columns);
  }

  public function createPermission($data){
    return $this->permission->create($data);
  }

  public function updatePermission(array $data, $id, $attribute="id") {
    return $this->permission->where($attribute, '=', $id)->update($data);
  }

  public function deletePermission($id) {
    return $this->permission->destroy($id);
  }

  public function findPermission($id, $columns = array('*')) {
    return $this->permission->find($id, $columns);
  }

  public function findPermissionBy($attribute, $value, $columns = array('*')) {
    return $this->permission->where($attribute, '=', $value)->first($columns);
  }

  public function assignRole(array $userattribute, $roleattributes){
    list($key, $value) = each($userattribute);
    $user = $this->user_rep->findUserBy($userattribute, false, ['id']);
    if (isset($roleattributes['selectedRoles'])) {
      foreach($roleattributes['selectedRoles'] as $roleattribute){
        $role = $this->findRoleBy("name" , $roleattribute, ['id']);
        if ($user && $role) {
            $user->roles()->attach($role->id);
        }
        else {
          return Larapi::respondForbidden();
        }
      }
    }
    else {
      $role = $this->findRoleBy("name" , $roleattributes['name'], ['id']);
        if ($user && $role) {
          $user->roles()->attach($role->id);
        }
        else {
          return Larapi::respondForbidden();
        }
    }

    return Larapi::respondOk();
  }

  public function attachPermission($roleattribute, $permattribute){

    list($key, $value) = each($roleattribute);
    $role = $this->findRoleBy('name' , $value, ['id']);
    list($key, $value) = each($permattribute);
    $permission =  $this->findPermissionBy($key , $value, ['id']);
    foreach ($role->perms as $key => $perms) {
      if ($perms->id == $permission->id) {
        return Larapi::respondOk("Permission already Added");
      }
    }
    if ( $permission && $role ) {
      $response = $role->attachPermission($permission);
       return Larapi::respondOk($response);
    } else {
       return Larapi::respondForbidden();
    }
  }

  public function  getUserRolesAndPerms(){
    $user = Auth::user();
    $response = NULL;
    if ( isset($user)) {
      $roles['roles'] = $user->roles;
      foreach ($roles['roles'] as $key => $value) {
        $this->role = $this->findRole($value->id);
        $response[$value->name] = $this->role->perms;
      }
      return Larapi::respondOk($response);
    }
  }


}
