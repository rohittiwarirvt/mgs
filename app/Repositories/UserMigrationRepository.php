<?php


namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Hash;
use App\Repositories\UserRepository;



class UserMigrationRepository
{
	public function __construct(UserRepository $user_repository){
		$this->user_repository = $user_repository;
		$this->old_mgs_db = DB::connection('mysql2');
	}
	public function migrateUser()
    {	
    	try{
    		ini_set('max_execution_time', 0); 
	    	$newmgsdb = DB::connection('mysql');
	    	$oldmgsdb = DB::connection('mysql2');


	    	$existing_emails = $newmgsdb->table('subscription')->lists('email');
	    	$existingusers = $oldmgsdb->table('mgs_user')->where('IsDeleted','=',0)->get();
	    	$records_fetched = count($existingusers);
	    	$user_records_created = 0;
	    	$roles_records_created = 0;
	    	$address_record_created = 0;
	    	$user_names_inserted = Array();
	    	$users_not_inserted = Array();
	    	$users_inserted = Array();
	    	$roles_array = [1,4,6,11,12,13,14];

	    	
	    	foreach ($existingusers as $existinguser){
	    		if(in_array($existinguser->UserName,$user_names_inserted)){
	    			$user_info = array(
	    							"user_id"=>$existinguser->UserID,
	    							"user_name"=>$existinguser->UserName,
	    							"Reason"=>"Duplicate UserName"
	    						 );
	    			array_push($users_not_inserted,$user_info);
	    			continue;
	    		}

	    		if(!in_array($existinguser->UserRoleID,$roles_array)){
	    			$user_info = array(
	    							"user_id"=>$existinguser->UserID,
	    							"user_name"=>$existinguser->UserName,
	    							"Reason"=>"RoleId is Not Present in DB"
	    						 );
	    			array_push($users_not_inserted,$user_info);
	    			continue;

	    		}

	    		$existingCustomer = $oldmgsdb->table('mgs_customer')->where('UserID','=',$existinguser->UserID)->get();


	    		//To DO Ipmort Address here if required.
	    	
	        	$user = new User;
	        	if(!$existinguser->OTP)
	        		$existinguser->OTP = "";
	        	if(!$existinguser->FirstName){
	        		if($existingCustomer)
	    				$existinguser->FirstName = $existingCustomer[0]->FirstName;
	    			else
	        			$existinguser->FirstName = "";
	        	}
	        	if(!$existinguser->LastName){
	        		if($existingCustomer)
	    				$existinguser->LastName = $existingCustomer[0]->LastName;
	    			else
	        			$existinguser->LastName = "";
	        	}
	        	if(!$existinguser->Mobile){
	        		if($existingCustomer)
	    				$existinguser->Mobile = $existingCustomer[0]->Mobile;
	    			else
	        			$existinguser->Mobile = "";
	        	}
	        	if(!$existinguser->Email){
	        		if($existingCustomer)
	    				$existinguser->Email = $existingCustomer[0]->Email;
	    			else
	        			$existinguser->Email = "";
	        	}
	        	if(!$existinguser->LastLoginTime)
	        		$existinguser->LastLoginTime = "";

	    		$userdetails = array(
	        		"id"=>$existinguser->UserID,
	        		"username"=>$existinguser->UserName,
	        		"email"=>$existinguser->Email,
	        		"password"=>Hash::make(base64_decode($existinguser->UserPassword)),
	        		"created_at"=>$existinguser->AddedDate,
	        		"updated_at"=>$existinguser->ModifiedDate,
	        		"phonenumber"=>$existinguser->Mobile,
	        		"verification_code"=>$existinguser->OTP,
	        		"created_by"=>$existinguser->AddedBy,
	        		"updated_by"=>$existinguser->ModifiedBy,
	        		"first_name"=>$existinguser->FirstName,
	        		"last_name"=>$existinguser->LastName,
	        		"last_access"=>$existinguser->LastLoginTime,
	        		"status"=>$existinguser->IsActive,
	        		"user_type"=>"registered",
	        		"is_migrated_user"=>1
	      		);

	      		$userroledeatils = array(
	      			"user_id"=>$existinguser->UserID,
	      			"role_id"=>$existinguser->UserRoleID
	      		);

	      		$id_exist = $newmgsdb->table('users')->where('id','=',$existinguser->UserID)->get();



	      		if($id_exist){
	      			$user_info = array(
	    							"user_id"=>$existinguser->UserID,
	    							"user_name"=>$existinguser->UserName,
	    							"Reason"=>"User ID Already Exist"
	    						 );
	    			array_push($users_not_inserted,$user_info);
	    			continue;
	      		}

	      		$if_username_exist = $newmgsdb->table('users')->where('username','=',$existinguser->UserName)->get();

				if($if_username_exist){
	      			$user_info = array(
	    							"user_id"=>$existinguser->UserID,
	    							"user_name"=>$existinguser->UserName,
	    							"Reason"=>"User Name Already Exist"
	    						 );
	    			array_push($users_not_inserted,$user_info);
	    			continue;
	      		}	      		

	      		$insert_user_result = $newmgsdb->table('users')->insert($userdetails);
	      		$insert_user_role_result = $newmgsdb->table('role_user')->insert($userroledeatils);
	      		if($insert_user_result == 1){
	      			$user_records_created++;			
	      			array_push($user_names_inserted,$existinguser->UserName);
	      		}
	      		if($insert_user_role_result == 1){
	      			array_push($users_inserted,$existinguser->UserID);
	      			$roles_records_created++;
	      			if(!in_array($existinguser->Email, $existing_emails) && $existinguser->Email != ""){
	      				 $subscription_details = array(
	      				 	"user_id"=>$existinguser->UserID,
	      				 	"source"=>"Migrated User",
	      				 	"token"=>0,
	      				 	"status"=>"subscribed",
	      				 	"email"=>$existinguser->Email,
	      				 	"created_at"=>date('Y-m-d H:i:s'),
	        				"updated_at"=>date('Y-m-d H:i:s')
	      				 );
	      				$subscription_result = $newmgsdb->table('subscription')->insert($subscription_details);
         			}
				}
			}
			if(count($users_not_inserted)>0){
				print_r("users_not_inserted=");
				foreach($users_not_inserted as $user_not_inserted){
					print_r($user_not_inserted);
					echo("<br/>");
				}
			}
			if(count($users_inserted)>0){
				foreach($users_inserted as $user_inserted){
					print_r("users_id_inserted=");
					print_r($user_inserted);
					echo("<br/>");
				}
			}
			return array('records_fetched'=>$records_fetched, 'user_records_inserted'=>$user_records_created,'roles_records_inserted'=>$roles_records_created,'error'=>'');
		}catch(Exception $e){
			return array('error'=>$e->getMessage());			
		}
	}

	public function migrateUserToOldApp($user_credentials){
		try{
	
			$user_details = DB::table('users')->where('username', '=', $user_credentials['username'])->get();

			$user_role_details = DB::table('role_user')->where('user_id', '=', $user_details[0]->id)->get();

			//Add error if more than one role is present.
			//Add error if role is not found.
		
			$old_app_user_details = array(
				"UserID"=>$user_details[0]->id,
				"OrgRelID"=>3,
				"UserRoleID"=>$user_role_details[0]->role_id,
				"UserName"=>$user_details[0]->username,
				"UserPassword"=>$user_details[0]->pass_copy,
				"Email"=>$user_details[0]->email,
				"Mobile"=>$user_details[0]->phonenumber,
				"IsActive"=>1,
				"IsDeleted"=>0,
				"AddedBy"=>$user_details[0]->created_by,
				"AddedDate"=>$user_details[0]->created_at,
				"ModifiedBy"=>$user_details[0]->updated_by,
				"ModifiedDate"=>$user_details[0]->updated_at,
				"IsLoginAllowed"=>1,
				"IsMobileActivated"=>0,
				"IsDesktopActivated"=>1,
				"OTP"=>$user_details[0]->verification_code,
				"FirstName"=>$user_details[0]->first_name,
				"LastName"=>$user_details[0]->last_name,
				"LastLoginTime"=>$user_details[0]->last_access
			);	

			$inserted_user_id = $this->old_mgs_db->table('mgs_user')->insertGetId($old_app_user_details);

      		$old_app_customer_details = array(
      			"FirstName"=>$user_details[0]->first_name,
      			"LastName"=>$user_details[0]->last_name,
      			"Email"=>$user_details[0]->email,
      			"Mobile"=>$user_details[0]->phonenumber,
      			"UserID"=>$user_details[0]->id,
      			"IsActive"=>1,
      			"IsDeleted"=>0,
      			"AddedBy"=>$user_details[0]->created_by,
      			"AddedDate"=>$user_details[0]->created_at,
      			"ModifiedBy"=>$user_details[0]->updated_by,
      			"ModifiedDate"=>$user_details[0]->updated_at
      		);

		    $inserted_customer_id = $this->old_mgs_db->table('mgs_customer')->insertGetId($old_app_customer_details); 
	
		}catch(Exception $e){
			return array('error'=>$e->getMessage());
		}
	}

}