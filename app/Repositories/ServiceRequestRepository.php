<?php

namespace App\Repositories;

use DB;
use Carbon\Carbon;
use App\Models\Quote;
use App\Models\ServiceRequest;
use App\Repositories\OldMgsRepository;
use App\Repositories\ProductRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\OptionAndOptionChoiceRepository;

class ServiceRequestRepository
{
  private $quote;
  private $old_mgs_repository;
  private $product_repository;
  private $attribute_repository;
  private $option_repository;
  private $new_mgs_db; 
  private $old_mgs_db;


  public function __construct(Quote $quote,ServiceRequest $ServiceRequest,OldMgsRepository $OldMgsRepository,
                              ProductRepository $ProductRepository,AttributeRepository $AttributeRepository,OptionAndOptionChoiceRepository $OptionAndOptionChoiceRepository){
    $this->new_mgs_db = DB::connection('mysql');
    $this->old_mgs_db = DB::connection('mysql2');
    $this->quote = $quote;
    $this->servicerequest = $ServiceRequest;
    $this->product_repository = $ProductRepository;
    $this->old_mgs_repository = $OldMgsRepository;
    $this->attribute_repository = $AttributeRepository;
    $this->option_repository = $OptionAndOptionChoiceRepository;
  }

  public function convertQuoteToSR($quotedetails) {
    try {
      $srdetails = array(
        "id"=>$quotedetails->id,
        "user_name"=>$quotedetails->user_name,
        "user_info"=>json_encode($quotedetails->user_information),
        "service_info"=>json_encode($quotedetails->quote_service_info),
        "appointment_date"=>$quotedetails->appointment_date,
        "appointment_time"=>$quotedetails->appointment_time,
        "sr_price"=>$quotedetails->quote_price,
        "end_date"=>$quotedetails->end_date,
        "status_id"=>$quotedetails->status_id,
        "expire_date"=>$quotedetails->expire_date,
        "source_id"=>$quotedetails->quote_source,
        "created_by"=>$quotedetails->created_by,
        "updated_by"=>$quotedetails->updated_by,
        "created_at"=>date('Y-m-d H:i:s'),
        "updated_at"=>date('Y-m-d H:i:s')
      );
        servicerequest::create($srdetails);
        return "success";    
    }catch(\Exception $e){
        return $e->getMessage();
    }
   }

  public function insertOldSR($quotedetails,$serviceinfos){
    try{
      
    $oldmgsdb = DB::connection('mysql2');

    $old_category_service_master_array = array(
        array("Service_Name"=>"Beauty Services","Category_Id"=>"2","Service_Id"=>"6"),
        array("Service_Name"=>"Movers & Packers","Category_Id"=>"8","Service_Id"=>"35"),
        array("Service_Name"=>"Pest Control","Category_Id"=>"17","Service_Id"=>"37"),
        array("Service_Name"=>"Plumber","Category_Id"=>"12","Service_Id"=>"50"),
        array("Service_Name"=>"Electrician","Category_Id"=>"12","Service_Id"=>"51"),
        array("Service_Name"=>"Home Cleaning","Category_Id"=>"12","Service_Id"=>"141"),
        array("Service_Name"=>"Pooja Services","Category_Id"=>"17","Service_Id"=>"46"),
        array("Service_Name"=>"Carpenter","Category_Id"=>"12","Service_Id"=>"55"),
        array("Service_Name"=>"Painting","Category_Id"=>"6","Service_Id"=>"21"),
        array("Service_Name"=>"Driver on Demand","Category_Id"=>"17","Service_Id"=>"139"),
        array("Service_Name"=>"Interior Services","Category_Id"=>"6","Service_Id"=>"22"),
        array("Service_Name"=>"Appliance Repair","Category_Id"=>"16","Air Conditioner"=>"136","Laptop"=>"138","Mobile/Smartphones"=>"137","Other Appliances"=>"142")
    );
      date_default_timezone_set('Asia/Calcutta');

      $product_details = $this->product_repository->find($serviceinfos[0]['product_id'])->services;

      if($product_details[0]->service_name != "Appliance Repair"){
        
      $sr_id = $this->insertSRTable($quotedetails);
      $customer_sr_id = $this->insertCustomerSRTable($sr_id,$quotedetails,$serviceinfos,$old_category_service_master_array);
      $customer_sr_details = $oldmgsdb->table('mgs_customerservicerequestrel')
                                    ->where('CustomerServiceRequestRelationID','=',$customer_sr_id)
                                    ->get();


      $service_id = $customer_sr_details[0]->ServiceID;
      if($service_id == 46)
          $sr_code_value_id = $this->insertSRCodeValuesForPooja($customer_sr_id,$quotedetails,$serviceinfos);
      if($service_id == 55)
          $sr_code_value_id = $this->insertSRCodeValuesForCarpenter($customer_sr_id,$quotedetails,$serviceinfos); 
      if($service_id == 50 || $service_id == 51)
          $sr_code_value_id = $this->insertSRCodeValuesForPlumbingOrElectrician($customer_sr_id,$quotedetails,$serviceinfos);
      if($service_id == 6)
          $sr_code_value_id = $this->insertSRCodeValuesForBeautician($customer_sr_id,$quotedetails,$serviceinfos);  
      if($service_id == 22) 
          $sr_code_value_id = $this->insertSRCodeValuesForInteriorServices($customer_sr_id,$quotedetails,$serviceinfos);   
      if($service_id == 139)
          $sr_code_value_id = $this->insertSRCodeValuesForDrivers($customer_sr_id,$quotedetails,$serviceinfos);
      if($service_id == 141)
          $sr_code_value_id = $this->insertSRCodeValuesForHomeCleaning($customer_sr_id,$quotedetails,$serviceinfos);
      if($service_id == 21)
          $sr_code_value_id = $this->insertSRCodeValuesForPainting($customer_sr_id,$quotedetails,$serviceinfos);
      if($service_id == 35)
          $sr_code_value_id = $this->insertSRCodeValuesForMoversAndPackers($customer_sr_id,$quotedetails,$serviceinfos);           
      if($service_id == 37)
          $sr_code_value_id = $this->insertSRCodeValuesForPestControl($customer_sr_id,$quotedetails,$serviceinfos);           

        

      return array('Service_Request_Id'=>$sr_id,'Customer_SR_ID'=>$customer_sr_id);
      }
      else{
        $sr_details = $this->insertSRCodeValuesForAppliances($quotedetails,$serviceinfos,$old_category_service_master_array);

        return array('Service_Request_Id'=>$sr_details["sr_ids"],'Customer_SR_ID'=>$sr_details["customer_sr_ids"]);

      }
     
    }catch(\Exception $e){
        return $e->getMessage();
    }
  }

  private function createSRAddress($quotedetails){

    $oldmgsdb = DB::connection('mysql2');

    $quote_info = $quotedetails->getData()->response;

    $user_info_json = json_decode($quote_info->user_information);

    $address_details = array(
        "Society_Building"=> $user_info_json->address1,
        "CityID"=>1,
        "StateID"=>1,
        "CountryID"=>1,
        "MobileNo1"=>$user_info_json->phonenumber,
        "Zip"=>$user_info_json->pincode,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$quote_info->user_id,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$quote_info->user_id,
        "ModifiedDate"=>date('Y-m-d H:i:s')
    );

    $address_id = $oldmgsdb->table('mgs_address')->insertGetId($address_details);

    $user_address_relation_details = array(
        "UserID"=>$quote_info->user_id,
        "OrgID"=>1,
        "OrgTypeID"=>2,
        "AddressID"=>$address_id,
        "IsDefault"=>0,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$quote_info->user_id,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$quote_info->user_id,
        "ModifiedDate"=>date('Y-m-d H:i:s')
    );

    $user_address_id = $oldmgsdb->table('mgs_useraddressrel')->insertGetId($user_address_relation_details);

    return $address_id;
  }

  private function insertSRTable($quotedetails){

      $oldmgsdb = DB::connection('mysql2');

      $quote_info = $quotedetails->getData()->response;

      $address_id = $this->createSRAddress($quotedetails);

      //$customer_address_details = $this->old_mgs_repository->getLatestUserAddress('UserID',$quote_info->user_id);

      //Adding two secs of delay because in case of appliance repair multiple SR's are created
      //with similar timestamps which disturbs the SR display order in backend tool.
      usleep(2000000);

      //currently passing default value for state and city. 

      $srdetails = array(
        "ServiceRequestID"=>$quote_info->id,
        "CustomerUserID"=>$quote_info->user_id,
        "CurrentStatus"=>1,
        "StateID"=>1,
        "CityID"=>1,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$quote_info->user_id,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$quote_info->user_id,
        "ModifiedDate"=>date('Y-m-d H:i:s'),
        "AddressID"=>$address_id,
        "IsEmailSend"=>1,
        "IsMobileRequest"=>0,
        "IpAddress"=>$_SERVER['REMOTE_ADDR'],
        "CallAttendedBy"=>0,
        "FollowupRequired"=>1,
        "RequestMonitoringBy"=>0
      );

      $sr_id = $oldmgsdb->table('mgs_servicerequest')->insertGetId($srdetails);
      return $sr_id;
  }



  private function insertCustomerSRForAppliance($sr_id,$quotedetails,$serviceinfo,$old_category_service_master_array){
      
      $newmgsdb = DB::connection('mysql');
      $oldmgsdb = DB::connection('mysql2');
      $old_category_id;
      $old_service_id;

      $service_details = $this->product_repository->find($serviceinfo['product_id'])->services;

      $product_details = $this->product_repository->find($serviceinfo['product_id']);

      $service_name_match_found = false;
      foreach($old_category_service_master_array as $service_data){
        if($service_data['Service_Name'] == $service_details[0]->service_name){
          foreach($service_data as $key => $value){
            if($key == $product_details->getAttributes()['product_name']){
            $service_name_match_found = true;
            $old_category_id = $service_data['Category_Id'];
            $old_service_id = $value;
            }
          }
        }
      }

      if($service_name_match_found == false){
        foreach($old_category_service_master_array as $service_data){
          if($service_data['Service_Name'] == $service_details[0]->service_name){
            foreach($service_data as $key => $value){
              if($key == "Other Appliances"){
              $old_category_id = $service_data['Category_Id'];
              $old_service_id = $value;
              }
            }
          }
        }        
      }

      $quote_info = $quotedetails->getData()->response;

      $customer_sr_details = array(
          "ServiceRequestID"=>$sr_id,
          "CustomerUserID"=>$quote_info->user_id,
          "CategoryID"=>$old_category_id,
          "ServiceID"=>$old_service_id,
          "CurrentStatus"=>1,
          "RequestedDate"=>$quote_info->appointment_date,
          "RequestedTime"=>$quote_info->appointment_time,
          "IsActive"=>1,
          "IsDeleted"=>0,
          "AddedBy"=>$quote_info->user_id,
          "AddedDate"=>date('Y-m-d H:i:s'),
          "ModifiedBy"=>$quote_info->user_id,
          "ModifiedDate"=>date('Y-m-d H:i:s')
      );

      $customer_sr_id = $oldmgsdb->table('mgs_customerservicerequestrel')->insertGetId($customer_sr_details);
      $customer_sr_details["customer_sr_id"] = $customer_sr_id;
      return $customer_sr_details;
    }


   private function insertCustomerSRTable($sr_id,$quotedetails,$serviceinfos,$old_category_service_master_array){

      $newmgsdb = DB::connection('mysql');
      $oldmgsdb = DB::connection('mysql2');
      $old_category_id;
      $old_service_id;
      //fetching service name and its ids.
      foreach($serviceinfos as $serviceinfo){
          $service_details = $newmgsdb->table('services')
                                      ->join('service_product','service_product.service_id','=','services.id')
                                      ->select('services.service_name')
                                      ->where('service_product.product_id', '=', $serviceinfo['product_id'])
                                      ->get();

          foreach($old_category_service_master_array as $service_data){
            if($service_data['Service_Name'] == $service_details[0]->service_name){
              $old_service_id = $service_data['Service_Id'];
              $old_category_id = $service_data['Category_Id'];
              break;
            }
          }
        
      }

      $quote_data = $quotedetails->getData()->response;

      $timezone = "UTC"; 
      $date = new \DateTime($quote_data->appointment_time, new \DateTimeZone($timezone));
      $date->setTimezone(new \DateTimeZone('Asia/Calcutta')); 
      $app_time = $date->format('g:i a');

      $customer_sr_details = array(
        "ServiceRequestID"=>$sr_id,
        "CustomerUserID"=>$quote_data->user_id,
        "CategoryID"=>$old_category_id,
        "ServiceID"=>$old_service_id,
        "CurrentStatus"=>1,
        "RequestedDate"=>$quote_data->appointment_date,
        "RequestedTime"=>$app_time,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$quote_data->user_id,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$quote_data->user_id,
        "ModifiedDate"=>date('Y-m-d H:i:s'),
        "SpecialRequirement"=>""
      );

      $customer_sr_id = $oldmgsdb->table('mgs_customerservicerequestrel')->insertGetId($customer_sr_details);
      return $customer_sr_id;
    }



  private function insertSRCodeValuesForPooja($customer_sr_id,$quotedetails,$serviceinfos){
    $newmgsdb = DB::connection('mysql');
    $oldmgsdb = DB::connection('mysql2');

    $customer_sr_details = $oldmgsdb->table('mgs_customerservicerequestrel')
                                    ->where('CustomerServiceRequestRelationID','=',$customer_sr_id)
                                    ->get();
  
    




      //creating sr code values for each service info record
      foreach($serviceinfos as $serviceinfo){
          
          $product_details = $newmgsdb->table('products')->select('product_name')
                                      ->where('id', '=', $serviceinfo['product_id'])
                                      ->get();

          if($product_details[0]->product_name == "Other Pooja")
              $product_details[0]->product_name = "Other";
          if($product_details[0]->product_name == "Satyanarayan/ Satya Amba")
              $product_details[0]->product_name = "Satyanarayan";
          if($product_details[0]->product_name == "Ghrihpravesh/ Vastu Shanti")
              $product_details[0]->product_name = "Ghrihpravesh";


          $specification_details = $oldmgsdb->table('mgs_specification')
                                        ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                        ->select('mgs_specification.SpecificationID')
                                        ->where('mgs_specification.SpecificationName','=',$product_details[0]->product_name)
                                        ->where('mgs_specification.IsActive','=',1)
                                        ->where('mgs_specification.IsDeleted','=',0)
                                        ->where('mgs_servicesepecification.ServiceID','=',$customer_sr_details[0]->ServiceID)
                                        ->get();


          $codeType_details = $oldmgsdb->table('mgs_codetype')->select('CodeTypeID','CodeTypeName')
                                       ->where('SpecificationID','=',$specification_details[0]->SpecificationID)
                                       ->where('IsActive','=',1)
                                       ->get();


          foreach($codeType_details as $codeType){
            if($codeType->CodeTypeName == "Material"){
            $code_details = $oldmgsdb->table('mgs_code')->where('CodeTypeID','=',$codeType->CodeTypeID)
                                                ->where('CityID','=',1)
                                                ->where('IsDeleted','=',0)
                                                ->get();
            
            foreach($code_details as $code){
              if(trim($code->CodeName) == "Pooja Without Material"){
                $final_code_id = $code->CodeID;
                $final_code_type_id = $code->CodeTypeID;
                $final_code_type_group_id = $code->CodeTypeGroup;
              }
            }
            }  

          }

          $sr_code_values_details = array(
            "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
            "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
            "ServiceID"=>$customer_sr_details[0]->ServiceID,
            "CategoryID"=>$customer_sr_details[0]->CategoryID,
            "SpecificationID"=>$specification_details[0]->SpecificationID,
            "CodeID"=>$final_code_id,
            "CodeTypeID"=>$final_code_type_id,
            "CodeTypeGroup"=>$final_code_type_group_id,
            "IsActive"=>1,
            "IsDeleted"=>0,
            "AddedBy"=>$customer_sr_details[0]->AddedBy,
            "AddedDate"=>date('Y-m-d H:i:s'),
            "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
            "ModifiedDate"=>date('Y-m-d H:i:s')
          );

          $sr_code_value_id = $oldmgsdb->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);
    } 
      
  }



  private function insertSRCodeValuesForCarpenter($customer_sr_id,$quotedetails,$serviceinfos){
    $newmgsdb = DB::connection('mysql');
    $oldmgsdb = DB::connection('mysql2');

    $customer_sr_details = $oldmgsdb->table('mgs_customerservicerequestrel')
                                    ->where('CustomerServiceRequestRelationID','=',$customer_sr_id)
                                    ->get();
  
    

    foreach($serviceinfos as $serviceinfo){

        $product_details = $newmgsdb->table('products')->select('product_name')
                                    ->where('id', '=', $serviceinfo['product_id'])
                                    ->get();

        $specification_details = $oldmgsdb->table('mgs_specification')
                                          ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                          ->select('mgs_specification.SpecificationID')
                                          ->where('mgs_specification.SpecificationName','=','Nature Of Request')
                                          ->where('mgs_specification.IsActive','=',1)
                                          ->where('mgs_specification.IsDeleted','=',0)
                                          ->where('mgs_servicesepecification.ServiceID','=',$customer_sr_details[0]->ServiceID)
                                          ->get();


        $codeType_details = $oldmgsdb->table('mgs_codetype')->select('CodeTypeID','CodeTypeName')
                                       ->where('SpecificationID','=',$specification_details[0]->SpecificationID)
                                       ->where('IsActive','=',1)
                                       ->get();


        $code_details = $oldmgsdb->table('mgs_code')->where('CodeTypeID','=',$codeType_details[0]->CodeTypeID)
                                                ->where('CityID','=',1)
                                                ->where('IsDeleted','=',0)
                                                ->get();
 
        
        foreach($code_details as $code){
          if(strcmp(trim($code->CodeName),trim($product_details[0]->product_name)) == 0){
            $final_code_id = $code->CodeID;
            $final_code_type_id = $code->CodeTypeID;
            $final_code_type_group_id = $code->CodeTypeGroup;
         }
        }


        $sr_code_values_details = array(
            "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
            "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
            "ServiceID"=>$customer_sr_details[0]->ServiceID,
            "CategoryID"=>$customer_sr_details[0]->CategoryID,
            "SpecificationID"=>$specification_details[0]->SpecificationID,
            "CodeID"=>$final_code_id,
            "CodeTypeID"=>$final_code_type_id,
            "CodeTypeGroup"=>$final_code_type_group_id,
            "IsActive"=>1,
            "IsDeleted"=>0,
            "AddedBy"=>$customer_sr_details[0]->AddedBy,
            "AddedDate"=>date('Y-m-d H:i:s'),
            "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
            "ModifiedDate"=>date('Y-m-d H:i:s')
          );

         $sr_code_value_id = $oldmgsdb->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
    }

  }

  private function insertSRCodeValuesForPlumbingOrElectrician($customer_sr_id,$quotedetails,$serviceinfos){

    $newmgsdb = DB::connection('mysql');
    $oldmgsdb = DB::connection('mysql2');

    $customer_sr_details = $oldmgsdb->table('mgs_customerservicerequestrel')
                                    ->where('CustomerServiceRequestRelationID','=',$customer_sr_id)
                                    ->get();

    foreach($serviceinfos as $serviceinfo){

      $product_details = $newmgsdb->table('products')->select('product_name')
                                  ->where('id', '=', $serviceinfo['product_id'])
                                  ->get();
      

      if($product_details[0]->product_name == "Bathroom Renovations"){
         $product_details[0]->product_name = "Other";
      }

      $specification_details = $oldmgsdb->table('mgs_specification')
                                        ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                        ->select('mgs_specification.SpecificationID','mgs_specification.SpecificationName')
                                        ->where('mgs_specification.SpecificationName','=',$product_details[0]->product_name)
                                        ->where('mgs_specification.IsActive','=',1)
                                        ->where('mgs_specification.IsDeleted','=',0)
                                        ->where('mgs_servicesepecification.ServiceID','=',$customer_sr_details[0]->ServiceID)
                                        ->get();
                         

      $codeType_details = $oldmgsdb->table('mgs_codetype')->select('CodeTypeID','CodeTypeName')
                                   ->where('SpecificationID','=',$specification_details[0]->SpecificationID)
                                   ->where('IsActive','=',1)
                                   ->get(); 


      $code_details = $oldmgsdb->table('mgs_code')->where('CodeTypeID','=',$codeType_details[0]->CodeTypeID)
                                                  ->where('CityID','=',1)
                                                  ->where('IsDeleted','=',0)
                                                  ->get();

      foreach($code_details as $code){
        if($code->CodeName == 1 || $code->CodeName == "Others" || $code->CodeName == $specification_details[0]->SpecificationName){
          $final_code_id = $code->CodeID;
          $final_code_type_id = $code->CodeTypeID;
          $final_code_type_group_id = $code->CodeTypeGroup;
        }
      }

        $sr_code_values_details = array(
            "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
            "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
            "ServiceID"=>$customer_sr_details[0]->ServiceID,
            "CategoryID"=>$customer_sr_details[0]->CategoryID,
            "SpecificationID"=>$specification_details[0]->SpecificationID,
            "CodeID"=>$final_code_id,
            "CodeTypeID"=>$final_code_type_id,
            "CodeTypeGroup"=>$final_code_type_group_id,
            "IsActive"=>1,
            "IsDeleted"=>0,
            "AddedBy"=>$customer_sr_details[0]->AddedBy,
            "AddedDate"=>date('Y-m-d H:i:s'),
            "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
            "ModifiedDate"=>date('Y-m-d H:i:s')
          );

        $sr_code_value_id = $oldmgsdb->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
    }
    
  } 

  private function insertSRCodeValuesForBeautician($customer_sr_id,$quotedetails,$serviceinfos){

    $newmgsdb = DB::connection('mysql');
    $oldmgsdb = DB::connection('mysql2');

    $customer_sr_details = $oldmgsdb->table('mgs_customerservicerequestrel')
                                    ->where('CustomerServiceRequestRelationID','=',$customer_sr_id)
                                    ->get();


    foreach($serviceinfos as $serviceinfo){

      $product_details = $newmgsdb->table('products')->select('product_name')
                                  ->where('id', '=', $serviceinfo['product_id'])
                                  ->get();

      $attribute_details = $newmgsdb->table('attributes')->select('attribute_name')
                                  ->where('id', '=', $serviceinfo['attribute_id'])
                                  ->where('is_active','=',1)
                                  ->get();

      $specification_details = $oldmgsdb->table('mgs_specification')
                                        ->join('mgs_servicesepecification','mgs_specification.SpecificationID','=','mgs_servicesepecification.SpecificationID')
                                        ->select('mgs_specification.SpecificationID')
                                        ->where('mgs_specification.SpecificationName','=',$product_details[0]->product_name)
                                        ->where('mgs_specification.IsActive','=',1)
                                        ->where('mgs_specification.IsDeleted','=',0)
                                        ->where('mgs_servicesepecification.ServiceID','=',$customer_sr_details[0]->ServiceID)
                                        ->get();
          

      $codeTypes =   $oldmgsdb->table('mgs_codetype')->select('CodeTypeID','CodeTypeName')
                              ->where('SpecificationID','=',$specification_details[0]->SpecificationID)
                              ->where('IsActive','=',1)
                              ->where('IsDeleted','=',0)
                              ->get();

       //check for attributes whose match was not found
       $attribute_details[0]->inserted = false;
       $highest_match = 0;

      foreach ($codeTypes as $codeType){
       
        $codeType->CodeTypeName = str_replace(" Price",'',$codeType->CodeTypeName);
        $codeType->CodeTypeName = str_replace(" price",'',$codeType->CodeTypeName);
        similar_text($codeType->CodeTypeName,$attribute_details[0]->attribute_name,$p);

        if($p>$highest_match){
          $highest_match = $p;
          $highest_match_CodeTypeID = $codeType->CodeTypeID;
          $highest_match_CodeTypeName = $codeType->CodeTypeName;
        }

        if(strcmp(trim($codeType->CodeTypeName),trim($attribute_details[0]->attribute_name)) == 0){

          $code_details = $oldmgsdb->table('mgs_code')->where('CodeTypeID','=',$codeType->CodeTypeID)
                                                      ->where('CityID','=',1)
                                                      ->where('IsDeleted','=',0)
                                                      ->get();

          $final_code_id = $code_details[0]->CodeID;
          $final_code_type_id = $code_details[0]->CodeTypeID;
          $final_code_type_group_id = $code_details[0]->CodeTypeGroup;

          $attribute_details[0]->inserted = true;          

          $sr_code_values_details = array(
              "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
              "CustomerServiceRequestRelationID"=>$customer_sr_id,
              "ServiceID"=>$customer_sr_details[0]->ServiceID,
              "CategoryID"=>$customer_sr_details[0]->CategoryID,
              "SpecificationID"=>$specification_details[0]->SpecificationID,
              "CodeID"=>$final_code_id,
              "CodeTypeID"=>$final_code_type_id,
              "CodeTypeGroup"=>$final_code_type_group_id,
              "IsActive"=>1,
              "IsDeleted"=>0,
              "AddedBy"=>$customer_sr_details[0]->AddedBy,
              "AddedDate"=>date('Y-m-d H:i:s'),
              "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
              "ModifiedDate"=>date('Y-m-d H:i:s')
          );

          $sr_code_value_id = $oldmgsdb->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
          }
      }
      
      //Insert For Attributes whose perfect match was not found.    
      if($attribute_details[0]->inserted == false){
            $code_details = $oldmgsdb->table('mgs_code')->where('CodeTypeID','=',$highest_match_CodeTypeID)
                                                        ->where('CityID','=',1)
                                                        ->where('IsDeleted','=',0)
                                                        ->get();

          $final_code_id = $code_details[0]->CodeID;
          $final_code_type_id = $code_details[0]->CodeTypeID;
          $final_code_type_group_id = $code_details[0]->CodeTypeGroup;

          $sr_code_values_details = array(
              "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
              "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
              "ServiceID"=>$customer_sr_details[0]->ServiceID,
              "CategoryID"=>$customer_sr_details[0]->CategoryID,
              "SpecificationID"=>$specification_details[0]->SpecificationID,
              "CodeID"=>$final_code_id,
              "CodeTypeID"=>$final_code_type_id,
              "CodeTypeGroup"=>$final_code_type_group_id,
              "IsActive"=>1,
              "IsDeleted"=>0,
              "AddedBy"=>$customer_sr_details[0]->AddedBy,
              "AddedDate"=>date('Y-m-d H:i:s'),
              "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
              "ModifiedDate"=>date('Y-m-d H:i:s')
          );

          $sr_code_value_id = $oldmgsdb->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
      }
    }     
  }

  private function insertSRCodeValuesForInteriorServices($customer_sr_id,$quotedetails,$serviceinfos){


    $customer_sr_details = $this->old_mgs_repository->getCustomerServiceRequestRelation('CustomerServiceRequestRelationID',$customer_sr_id);

    foreach($serviceinfos as $serviceinfo){

      $product_details = $this->product_repository->findProductBy('id', $serviceinfo['product_id'], ['product_name']);

      $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);

      $option_details = $this->option_repository->findOptionBy('id',$serviceinfo['option_id']);

      if($option_details->id == 16)
          $option_details->option_name = "Other";

      if($attribute_details->id == 113)
        $attribute_details->attribute_name = "Room Selection";

      $codeType_details = $this->old_mgs_repository->getCodeTypeByNameAndService($attribute_details->attribute_name,$customer_sr_details[0]->ServiceID);                               

      $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$codeType_details[0]->CodeTypeID);
      
      
      foreach($code_details as $code){
        if(str_replace(' ','',$option_details->option_name) == str_replace(' ','',$code->CodeName)){


          $final_code_id = $code->CodeID;
          $final_code_type_id = $code->CodeTypeID;
          $final_code_type_group_id = $code->CodeTypeGroup;
          $final_specification_id = $codeType_details[0]->SpecificationID;


          $sr_code_values_details = array(
              "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
              "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
              "ServiceID"=>$customer_sr_details[0]->ServiceID,
              "CategoryID"=>$customer_sr_details[0]->CategoryID,
              "SpecificationID"=>$final_specification_id, 
              "CodeID"=>$final_code_id,
              "CodeTypeID"=>$final_code_type_id,
              "CodeTypeGroup"=>$final_code_type_group_id,
              "IsActive"=>1,
              "IsDeleted"=>0,
              "AddedBy"=>$customer_sr_details[0]->AddedBy,
              "AddedDate"=>date('Y-m-d H:i:s'),
              "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
              "ModifiedDate"=>date('Y-m-d H:i:s')
          );

          $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  

        }
      }
    }

  }

  private function insertSRCodeValuesForDrivers($customer_sr_id,$quotedetails,$serviceinfos){

    $customer_sr_details = $this->old_mgs_repository->getCustomerServiceRequestRelation('CustomerServiceRequestRelationID',$customer_sr_id);

    foreach($serviceinfos as $serviceinfo){
    
    $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);   

    $option_details = $this->option_repository->findOption($serviceinfo['option_id']);

    if($attribute_details->attribute_name == "Duration")
      $spec_id = 183;
    if($attribute_details->attribute_name == "Overnight")
      $spec_id = 185;
    if($attribute_details->attribute_name == "Vehicle Type")
      $spec_id = 182;
    if($attribute_details->attribute_name == "No. of Days")
      $spec_id = 184;


    $codeType_details = $this->old_mgs_repository->getCodeTypeBySpecificationIdAndService($spec_id,$customer_sr_details[0]->ServiceID);

    $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$codeType_details[0]->CodeTypeID);
    $highest_match = 0;
    $match_found = false;
    foreach($code_details as $code){
      similar_text($code->CodeName,$option_details->option_name,$percentage_match);
      if($percentage_match>$highest_match){
        $highest_match = $percentage_match;
        $highest_match_name = $code->CodeName;
      }
      if($code->CodeName == $option_details->option_name){
       $match_found = true;

        $final_code_id = $code->CodeID;
        $final_code_type_id = $code->CodeTypeID;
        $final_code_type_group_id = $code->CodeTypeGroup;
        $final_specification_id = $codeType_details[0]->SpecificationID;

        $sr_code_values_details = array(
              "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
              "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
              "ServiceID"=>$customer_sr_details[0]->ServiceID,
              "CategoryID"=>$customer_sr_details[0]->CategoryID,
              "SpecificationID"=>$final_specification_id, 
              "CodeID"=>$final_code_id,
              "CodeTypeID"=>$final_code_type_id,
              "CodeTypeGroup"=>$final_code_type_group_id,
              "IsActive"=>1,
              "IsDeleted"=>0,
              "AddedBy"=>$customer_sr_details[0]->AddedBy,
              "AddedDate"=>date('Y-m-d H:i:s'),
              "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
              "ModifiedDate"=>date('Y-m-d H:i:s')
        );

        $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
     }
    }
    if($match_found == false){
      foreach($code_details as $codes)
        if($codes->CodeName == $highest_match_name){

        $final_code_id = $codes->CodeID;
        $final_code_type_id = $codes->CodeTypeID;
        $final_code_type_group_id = $codes->CodeTypeGroup;
        $final_specification_id = $codeType_details[0]->SpecificationID;

        $sr_code_values_details = array(
              "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
              "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
              "ServiceID"=>$customer_sr_details[0]->ServiceID,
              "CategoryID"=>$customer_sr_details[0]->CategoryID,
              "SpecificationID"=>$final_specification_id, 
              "CodeID"=>$final_code_id,
              "CodeTypeID"=>$final_code_type_id,
              "CodeTypeGroup"=>$final_code_type_group_id,
              "IsActive"=>1,
              "IsDeleted"=>0,
              "AddedBy"=>$customer_sr_details[0]->AddedBy,
              "AddedDate"=>date('Y-m-d H:i:s'),
              "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
              "ModifiedDate"=>date('Y-m-d H:i:s')
          );
        
        $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
          
        }
    }
    }  

  }

  private function insertSRCodeValuesForHomeCleaning($customer_sr_id,$quotedetails,$serviceinfos){
    $customer_sr_details = $this->old_mgs_repository->getCustomerServiceRequestRelation('CustomerServiceRequestRelationID',$customer_sr_id);


    foreach($serviceinfos as $serviceinfo){  
      $product_details = $this->product_repository->find($serviceinfo['product_id']);

      $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);  


      $specification_details = $this->old_mgs_repository->getSpecificationByNameAndServiceID($product_details->product_name,$customer_sr_details[0]->ServiceID);

      $code_type_details = $this->old_mgs_repository->getCodeTypeBySpecificationIdAndService($specification_details[0]->SpecificationID,$customer_sr_details[0]->ServiceID);

      
      $highest_match = 0;
      $higest_match_code_type;
      foreach($code_type_details as $code_type_detail){
        $code_name = explode('(',$code_type_detail->CodeTypeName);
        similar_text($code_name[0],$attribute_details->attribute_name,$percentage_match);
        if($percentage_match>$highest_match){
          $highest_match = $percentage_match;
          $higest_match_code_type = $code_type_detail;
        }
      }

      $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$higest_match_code_type->CodeTypeID);

      $final_code_id = $code_details[0]->CodeID;
      $final_code_type_id = $code_details[0]->CodeTypeID;
      $final_code_type_group_id = $code_details[0]->CodeTypeGroup;
      $final_specification_id = $higest_match_code_type->SpecificationID;

      $sr_code_values_details = array(
        "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
        "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
        "ServiceID"=>$customer_sr_details[0]->ServiceID,
        "CategoryID"=>$customer_sr_details[0]->CategoryID,
        "SpecificationID"=>$final_specification_id, 
        "CodeID"=>$final_code_id,
        "CodeTypeID"=>$final_code_type_id,
        "CodeTypeGroup"=>$final_code_type_group_id,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$customer_sr_details[0]->AddedBy,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
        "ModifiedDate"=>date('Y-m-d H:i:s')
      );
        
        $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
    }
  }

  private function checkTypeOfHome($serviceinfos){

    $type_of_home = "Attribute Not Found";
    foreach($serviceinfos as $serviceinfo){

      $product_details = $this->product_repository->find($serviceinfo['product_id']);
      
      if($product_details->product_name == "Type of home"){

        $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);  
        $trimmed_name = str_replace(' ', '', $attribute_details->attribute_name);
        $type_of_home = $trimmed_name;
        return $type_of_home;
      }
    }

    return $type_of_home;
  }

  private function insertSRCodeValuesForMoversAndPackers($customer_sr_id,$quotedetails,$serviceinfos){

    $customer_sr_details = $this->old_mgs_repository->getCustomerServiceRequestRelation('CustomerServiceRequestRelationID',$customer_sr_id);

    foreach($serviceinfos as $serviceinfo){

      $product_details = $this->product_repository->find($serviceinfo['product_id']);
      
      $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);  

      if($attribute_details->attribute_name == "1BHK")
        $spec_id = 56;
      if($attribute_details->attribute_name == "2BHK")
        $spec_id = 57;
      if($attribute_details->attribute_name == "3BHK")
        $spec_id = 58;
      if($attribute_details->attribute_name == "4BHK")
        $spec_id = 59;
      if($attribute_details->attribute_name == "5BHK")
        $spec_id = 60;
      if($attribute_details->attribute_name == "Others")
        $spec_id = 61;
      if($attribute_details->attribute_name == "Within City")
        $spec_id = 153;
      if($attribute_details->attribute_name == "Outside City")
        $spec_id = 154;

      $code_type_details = $this->old_mgs_repository->getCodeTypeBySpecificationIdAndService($spec_id,$customer_sr_details[0]->ServiceID);

      $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$code_type_details[0]->CodeTypeID);

      $sr_code_values_details = array(
        "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
        "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
        "ServiceID"=>$customer_sr_details[0]->ServiceID,
        "CategoryID"=>$customer_sr_details[0]->CategoryID,
        "SpecificationID"=>$spec_id, 
        "CodeID"=>$code_details[0]->CodeID,
        "CodeTypeID"=>$code_details[0]->CodeTypeID,
        "CodeTypeGroup"=>$code_details[0]->CodeTypeGroup,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$customer_sr_details[0]->AddedBy,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
        "ModifiedDate"=>date('Y-m-d H:i:s')
      );

      $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
    }
  }

  private function insertSRCodeValuesForPestControl($customer_sr_id,$quotedetails,$serviceinfos){

    $customer_sr_details = $this->old_mgs_repository->getCustomerServiceRequestRelation('CustomerServiceRequestRelationID',$customer_sr_id);

    foreach($serviceinfos as $serviceinfo){

      $product_details = $this->product_repository->find($serviceinfo['product_id']);

      $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name','attribute_description']);  

      $code_id = 0;
      if($product_details->product_name == "Cockroach Control Treatment"){
        $spec_id = 209;
        if($attribute_details->attribute_name == "1BHK" && $attribute_details->attribute_description == "Single Service")
          $code_id = 3028;
        if($attribute_details->attribute_name == "1BHK" && $attribute_details->attribute_description == "AMC (Three Services/ year)")
          $code_id = 3032;
        if($attribute_details->attribute_name == "2BHK" && $attribute_details->attribute_description == "Single Service")
          $code_id = 3029;
        if($attribute_details->attribute_name == "2BHK" && $attribute_details->attribute_description == "AMC (Three Services/ year)")
          $code_id = 3033;
        if($attribute_details->attribute_name == "3BHK" && $attribute_details->attribute_description == "Single Service")
          $code_id = 3030;
        if($attribute_details->attribute_name == "3BHK" && $attribute_details->attribute_description == "AMC (Three Services/ year)")
          $code_id = 3034;
        if($attribute_details->attribute_name == "4BHK" && $attribute_details->attribute_description == "Single Service")
          $code_id = 3031;
        if($attribute_details->attribute_name == "4BHK" && $attribute_details->attribute_description == "AMC (Three Services/ year)")
          $code_id = 3035;
        if($attribute_details->attribute_name == "Bungalow/Independent House/Villa")
          $code_id = 3036;
        if($attribute_details->attribute_name == "Commercial Premises")
          $code_id = 3037;
      }
      if($product_details->product_name == "Bed Bugs Control treatment"){
        $spec_id = 210;
         if($attribute_details->attribute_name == "1BHK" && $attribute_details->attribute_description == "Two Services Contract")
          $code_id = 3038;
         if($attribute_details->attribute_name == "1BHk" && $attribute_details->attribute_description == "AMC (Four Services/ year)")
          $code_id = 3042;
         if($attribute_details->attribute_name == "2BHK" && $attribute_details->attribute_description == "Two Services Contract")
          $code_id = 3039;
         if($attribute_details->attribute_name == "2BHK" && $attribute_details->attribute_description == "AMC (Four Services/ year)")
          $code_id = 3043;
         if($attribute_details->attribute_name == "3BHK" && $attribute_details->attribute_description == "Two Services Contract")
          $code_id = 3040;
         if($attribute_details->attribute_name == "3BHK" && $attribute_details->attribute_description == "AMC (Four Services/ year)")
          $code_id = 3044;
         if($attribute_details->attribute_name == "4BHK" && $attribute_details->attribute_description == "Two Services Contract")
          $code_id = 3041;
         if($attribute_details->attribute_name == "4BHK" && $attribute_details->attribute_description == "AMC (Four Services/ year)")
          $code_id = 3045;
         if($attribute_details->attribute_name == "Bungalow/Independent House/Villa")
          $code_id = 3046;
         if($attribute_details->attribute_name == "Commercial Premises")
          $code_id = 3047;
      }
      if($product_details->product_name == "Rodent Control Treatment"){
        $spec_id = 212;
        if($attribute_details->attribute_name == "1BHK")
          $code_id = 3054;
        if($attribute_details->attribute_name == "2BHK")
          $code_id = 3055;
        if($attribute_details->attribute_name == "3BHK")
          $code_id = 3056;
        if($attribute_details->attribute_name == "4BHK")
          $code_id = 3057;
        if($attribute_details->attribute_name == "Bungalow/Independent House/Villa")
          $code_id = 3058;
        if($attribute_details->attribute_name == "Commercial Premises")
          $code_id = 3059;
      }
      if($product_details->product_name == "Termite Control Treatment"){
        $spec_id = 211;
        if($attribute_details->attribute_name == "1BHK")
          $code_id = 3054;
        if($attribute_details->attribute_name == "2BHK")
          $code_id = 3054;
        if($attribute_details->attribute_name == "3BHK")
          $code_id = 3054;
        if($attribute_details->attribute_name == "4BHK")
          $code_id = 3054;
        if($attribute_details->attribute_name == "Bungalow/Independent House/Villa")
          $code_id = 3058;
        if($attribute_details->attribute_name == "Commercial Premises")
          $code_id = 3059;
      }
      if($product_details->product_name == "Wood Borer Control Treatment"){
        $spec_id = 213;
        if($attribute_details->attribute_name == "1BHK")
          $code_id = 3060;
        if($attribute_details->attribute_name == "2BHK")
          $code_id = 3061;
        if($attribute_details->attribute_name == "3BHK")
          $code_id = 3062;
        if($attribute_details->attribute_name == "4BHK")
          $code_id = 3063;
        if($attribute_details->attribute_name == "Bungalow/Independent House/Villa")
          $code_id = 3064;
        if($attribute_details->attribute_name == "Commercial Premises")
          $code_id = 3065;
      }
      if($product_details->product_name == "Mosquito Control Treatment"){
        $spec_id = 214;
         if($attribute_details->attribute_name == "1BHK")
          $code_id = 3066;
        if($attribute_details->attribute_name == "2BHK")
          $code_id = 3067;
        if($attribute_details->attribute_name == "3BHK")
          $code_id = 3068;
        if($attribute_details->attribute_name == "4BHK")
          $code_id = 3069;
        if($attribute_details->attribute_name == "Bungalow/Independent House/Villa")
          $code_id = 3070;
        if($attribute_details->attribute_name == "Commercial Premises")
          $code_id = 3071;
      }

      $code_type_details = $this->old_mgs_repository->getCodeTypeBySpecificationIdAndService($spec_id,$customer_sr_details[0]->ServiceID);

      $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$code_type_details[0]->CodeTypeID);


      foreach ($code_details as $code_detail) {
        if($code_detail->CodeID == $code_id){
            $final_code = $code_detail;
        }
      }

      $sr_code_values_details = array(
        "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
        "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
        "ServiceID"=>$customer_sr_details[0]->ServiceID,
        "CategoryID"=>$customer_sr_details[0]->CategoryID,
        "SpecificationID"=>$spec_id, 
        "CodeID"=>$final_code->CodeID,
        "CodeTypeID"=>$final_code->CodeTypeID,
        "CodeTypeGroup"=>$final_code->CodeTypeGroup,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$customer_sr_details[0]->AddedBy,
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
        "ModifiedDate"=>date('Y-m-d H:i:s')
      );

      $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
      }


  }

  private function insertSRCodeValuesForPainting($customer_sr_id,$quotedetails,$serviceinfos){

    $type_of_home = "None";
    $type_of_home = $this->checkTypeOfHome($serviceinfos);

    $customer_sr_details = $this->old_mgs_repository->getCustomerServiceRequestRelation('CustomerServiceRequestRelationID',$customer_sr_id);

    foreach($serviceinfos as $serviceinfo) {

      $product_details = $this->product_repository->find($serviceinfo['product_id']);

      $product_to_skip = array("Type of home","Carpet Area");

      if(!in_array($product_details->product_name,$product_to_skip)){

          $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);  
          
          $attr_name = $attribute_details->attribute_name;
        
          $attribute_to_skip = array("Textured Painting","Water Proofing");
          if(!in_array($attribute_details->attribute_name,$attribute_to_skip)){
          
              $spec_id = "";
              if($attribute_details->attribute_name == "Interior Painting")
                $spec_id = 54;
              else if($attribute_details->attribute_name == "Exterior Painting")
                $spec_id = 55;

              $code_type_details = $this->old_mgs_repository->getCodeTypeBySpecificationIdAndService($spec_id,$customer_sr_details[0]->ServiceID);


              $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$code_type_details[0]->CodeTypeID);

              $final_code = "";
              foreach($code_details as $code){  
                $trimmed_code_name = str_replace(' ', '', $code->CodeName);
                if($trimmed_code_name == $type_of_home || $trimmed_code_name == "Luster"){
                  $final_code = $code;
                }
              }


                if(!$final_code){
                  $final_code_id = 366;
                  $final_code_type_id = 130;
                  $final_code_type_group = 14;
                }else{
                  $final_code_id = $final_code->CodeID; 
                  $final_code_type_id = $final_code->CodeTypeID;
                  $final_code_type_group = $final_code->CodeTypeGroup;
                }

              $sr_code_values_details = array(
                "CustomerUserID"=>$customer_sr_details[0]->CustomerUserID,
                "CustomerServiceRequestRelationID"=>$customer_sr_details[0]->CustomerServiceRequestRelationID,
                "ServiceID"=>$customer_sr_details[0]->ServiceID,
                "CategoryID"=>$customer_sr_details[0]->CategoryID,
                "SpecificationID"=>$spec_id, 
                "CodeID"=>$final_code_id,
                "CodeTypeID"=>$final_code_type_id,
                "CodeTypeGroup"=>$final_code_type_group,
                "IsActive"=>1,
                "IsDeleted"=>0,
                "AddedBy"=>$customer_sr_details[0]->AddedBy,
                "AddedDate"=>date('Y-m-d H:i:s'),
                "ModifiedBy"=>$customer_sr_details[0]->ModifiedBy,
                "ModifiedDate"=>date('Y-m-d H:i:s')
              );
              $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
          }

      }

    }    
    
  }

  private function insertSRCodeValuesForAppliances($quotedetails,$serviceinfos,$old_category_service_master_array){

    $sr_ids = array();
    $customer_sr_ids = array();
    $sr_code_value_ids = array();
    $return_array = array();


    //Array to make sure that only one SR is created for each product.
    $repeated_products = array();

    foreach ($serviceinfos as $serviceinfo) {

      $product_details = $this->product_repository->find($serviceinfo['product_id']);

      //If Product has already been inserted then dont call.
      if(!in_array($serviceinfo['product_id'],$repeated_products)){
        $sr_id = $this->insertSRTable($quotedetails);
        array_push($sr_ids,$sr_id);
      }
      
      //If Product has already been inserted then dont call.
      if(!in_array($serviceinfo['product_id'],$repeated_products)){
        $customer_sr_details = $this->insertCustomerSRForAppliance($sr_id,$quotedetails,$serviceinfo,$old_category_service_master_array);
        array_push($customer_sr_ids,$customer_sr_details['customer_sr_id']);
      }  

      array_push($repeated_products,$serviceinfo['product_id']);

      $attribute_details = $this->attribute_repository->findAttributeBy(['id' => $serviceinfo['attribute_id']],['id','attribute_name']);  

      //select particular specification name according to different services
      if($customer_sr_details['ServiceID'] == 136)
        $specification_name = "Nature and Service";
      else if($customer_sr_details['ServiceID'] == 137)
        $specification_name = "Nature Of Problem";
      else if($customer_sr_details['ServiceID'] == 138)
        $specification_name = "Laptop";
      else if($customer_sr_details['ServiceID'] == 142)
        $specification_name = "Other Appliances";



      $specification_details = $this->old_mgs_repository->getSpecificationByNameAndServiceID($specification_name,$customer_sr_details['ServiceID']);

      $codeType_details = $this->old_mgs_repository->getCodeTypeBySpecificationIdAndService($specification_details[0]->SpecificationID,$customer_sr_details['ServiceID']);

      $code_details = $this->old_mgs_repository->findCodeBy('CodeTypeID',$codeType_details[0]->CodeTypeID);

      $highest_match = 0;
      $higest_match_code;
      foreach($code_details as $code){
        if($customer_sr_details['ServiceID'] != 142){
          similar_text($code->CodeName,$attribute_details->attribute_name,$percentage_match);
          if($percentage_match>$highest_match){
            $highest_match = $percentage_match;
            $higest_match_code = $code;
          }
        }
        else if($customer_sr_details['ServiceID'] == 142){
          similar_text($code->CodeName,$product_details->product_name,$percentage_match);
          if($percentage_match>$highest_match){
            $highest_match = $percentage_match;
            $higest_match_code = $code;
          }
        }
      }

      $final_code_id = $higest_match_code->CodeID;
      $final_code_type_id = $higest_match_code->CodeTypeID;
      $final_code_type_group_id = $higest_match_code->CodeTypeGroup;
      $final_specification_id = $specification_details[0]->SpecificationID;


      $sr_code_values_details = array(
        "CustomerUserID"=>$customer_sr_details['CustomerUserID'],
        "CustomerServiceRequestRelationID"=>$customer_sr_details['customer_sr_id'],
        "ServiceID"=>$customer_sr_details['ServiceID'],
        "CategoryID"=>$customer_sr_details['CategoryID'],
        "SpecificationID"=>$final_specification_id, 
        "CodeID"=>$final_code_id,
        "CodeTypeID"=>$final_code_type_id,
        "CodeTypeGroup"=>$final_code_type_group_id,
        "IsActive"=>1,
        "IsDeleted"=>0,
        "AddedBy"=>$customer_sr_details['AddedBy'],
        "AddedDate"=>date('Y-m-d H:i:s'),
        "ModifiedBy"=>$customer_sr_details['ModifiedBy'],
        "ModifiedDate"=>date('Y-m-d H:i:s')
     );

      $sr_code_value_id = $this->old_mgs_db->table('mgs_servicerequestcodevalues')->insertGetId($sr_code_values_details);  
      array_push($sr_code_value_ids,$sr_code_value_id);
    }

    $return_array["sr_ids"] = $sr_ids; 
    $return_array["customer_sr_ids"] = $customer_sr_ids;
    $return_array["sr_code_value_ids"] = $sr_code_value_ids; 
    
    return $return_array;
  }

}

?>