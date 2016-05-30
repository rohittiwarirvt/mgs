<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Repositories\UserMigrationRepository;

class UserMigrationController extends Controller
{
    public function __construct(UserMigrationRepository $UserMigrationRepository)
    {   
		$this->usermigrationrepository = $UserMigrationRepository;
    }

    public function migrateUser()
    {
    	$result = $this->usermigrationrepository->migrateUser();
    	if($result['error'])
    		return response()->json(['error'=>$result['error']]);
    	else  	
    		return response()->json(['UserRecordsFectched'=>$result['records_fetched'], 'UserRecordsCreated'=>$result['user_records_inserted'],"RolesRecordsCreated"=>$result['roles_records_inserted']]);  	
    }

    public function checkDB(){
        try{
            $result = DB::connection('mysql2')->getDatabaseName();
            return response()->json(['status'=>'success','dbName'=>$result]);
        }catch(Exception $e){
            return response()->json(['status'=>'error','Message'=>$e->getMessage()]);
        }
    }
}


