<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Visitor;
use \Prettus\Validator\Exceptions\ValidatorException;
use MGS\Services\Validation\VisitorValidator;
use MGS\Services\Validation\RegistrationValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;
use Larapi;
use DB;

class UserRepository
{

  private $user;
  protected $visitor_validator;

  public function __construct(User $user, Visitor $visitor, VisitorValidator $visitor_validator, RegistrationValidator $registration_validator) {
    $this->user = $user;
    $this->visitor = $visitor;
    $this->visitor_validator = $visitor_validator;
    $this->registration_validator = $registration_validator;
    $this->oldmgsdb = DB::connection('mysql2');
  }


  public function getLastUserId(){
    $user_id = $this->oldmgsdb->table('mgs_user')->max('UserId');
    return $user_id;
  }

  public function createUser($data) {
    try {
      $validate_data = $this->registration_validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE );
      $data['id'] = $this->getLastUserId() + 1;

      $user = $this->user->create($data);
      return Larapi::respondOk($user);
    }
     catch (ValidatorException $e) {
      return Larapi::respondBadRequest($e->getMessageBag(), 400);
     }

  }

  public function update(array  $data, $id, $attribute = 'id') {
    return $this->user->where($attribute, '=', $id)->update($data);
  }

  public function find($id, $columns = array('*')) {
    return $this->user->find($id, $columns);
  }

  public function findUserBy($matches, $or = 'false', $columns = array('*')) {
    if ($or) {
      $query = $this->user->query();
      foreach ($matches as $key => $value) {
          $query->orWhere($key, $value);
      }
        return $query->first($columns);
    } else {
      return $this->user->where($matches)->first($columns);
    }

  }

  public function getAllUsers($columns = array('*')) {
    return $this->user->all();
  }

  public function checkIfUserExists($findParams) {

       $findParams = array_filter($findParams);
       $result = $this->findUserBy($findParams, true);

       if(empty($result['email']) && isset($findParams['email'])) {
        $email['email'] = $findParams['email'];
        $this->update($email, $result['id']);
       }

      if($result){
        return Larapi::respondOk($result);
       }
       else {
        return Larapi::respondNotFound();
       }
  }

  public function createVisitor($data) {
    try {
      $validate_data = $this->visitor_validator->with($data)->passesOrFail();
      $user = $this->visitor->create($data);
      return Larapi::respondOk($user);
    }
     catch (ValidatorException $e) {
      return Larapi::respondBadRequest($e->getMessageBag(), 400);
     }

  }

  public function setUserPassword($data){
    $update_data['password'] = bcrypt($data['password']);
    $update_data['pass_copy'] = base64_encode($data['password']);
    $update_data['user_type'] = 'registered';
    return $this->update($update_data, $data['user_id']);
  }
}
