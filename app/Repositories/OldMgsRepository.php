<?php

namespace App\Repositories;
use DB;

class OldMgsRepository
{

 private $old_mgs_db;

 public function __construct() {
    $this->old_mgs_db = DB::connection('mysql2');
 }

 public function getCustomerServiceRequestRelation($attribute,$value) {

	$customer_sr_details = $this->old_mgs_db->table('mgs_customerservicerequestrel')
                                            ->where($attribute,'=',$value)
                                            ->get();

    return $customer_sr_details;
 }

 public function getCodeTypeByNameAndService($code_type_name,$service_id){

	$code_type_details = $this->old_mgs_db->table('mgs_codetype')
                                          ->join('mgs_specification','mgs_codetype.SpecificationID','=','mgs_specification.SpecificationID')
                                          ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                          ->select('mgs_codetype.*')
                                          ->where('mgs_codetype.CodeTypeName','=',$code_type_name)
                                          ->where('mgs_specification.IsActive','=',1)
                                          ->where('mgs_specification.IsDeleted','=',0)
                                          ->where('mgs_servicesepecification.ServiceID','=',$service_id)
                                          ->get();

    return $code_type_details;
 }

 public function findCodeBy($attribute, $value) {
 
 	$code_details = $this->old_mgs_db->table('mgs_code')->where($attribute, '=', $value)
 													         ->where('IsDeleted','=',0)
 													         ->where('CityID','=',1)
 													         ->get();
 	return $code_details;
  }

  public function getCodeTypeBySpecificationIdAndService($pecification_id,$service_id){

    $code_type_details = $this->old_mgs_db->table('mgs_codetype')
                                          ->join('mgs_specification','mgs_codetype.SpecificationID','=','mgs_specification.SpecificationID')
                                          ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                          ->select('mgs_codetype.*')
                                          ->where('mgs_codetype.SpecificationID','=',$pecification_id)
                                          ->where('mgs_specification.IsActive','=',1)
                                          ->where('mgs_specification.IsDeleted','=',0)
                                          ->where('mgs_servicesepecification.ServiceID','=',$service_id)
                                          ->get();

    return $code_type_details;
 }

  public function getSpecificationByNameAndServiceID($name,$serviceId){
    
    $specification_details = $this->old_mgs_db->table('mgs_specification')
                                  ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                  ->select('mgs_specification.SpecificationID')
                                  ->where('mgs_specification.SpecificationName','=',$name)
                                  ->where('mgs_specification.IsActive','=',1)
                                  ->where('mgs_specification.IsDeleted','=',0)
                                  ->where('mgs_servicesepecification.ServiceID','=',$serviceId)
                                  ->get();

    return $specification_details;

  }

  public function getLatestUserAddress($attribute,$value){

    $customer_address_details = $this->old_mgs_db->table('mgs_useraddressrel')
                                     ->where($attribute, '=', $value)
                                     ->orderBy('AddedDate','desc')->first();
                                     
    return $customer_address_details;
  }

}