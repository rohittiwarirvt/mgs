<?php

namespace App\Http\Controllers;
use App\Models\AddressBook;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use DB;

class AddressBookController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
    }

    public function index()
    {
       $user = JWTAuth::parseToken()->authenticate();

    }

    public function store(Request $request)
    {
        $rules = array(
            'first_name'      => 'required',
            'last_name'       => 'required',
            'address_line1'   => 'required',
            'address_line2'   => 'required',
            'pincode'         => 'required',
            'country'         => 'required',
            'state'           => 'required',
            'city'            => 'required',
            'email'           => 'email',
            'phone_number'    => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();

            $data = array('error_msg' => $messages);
            return json_encode($data);
        }
        else
        {
            $user = JWTAuth::parseToken()->authenticate();
            $uid = $user->id;
            $data = $request->all();
            $newaddressbook = $data;
            $newaddressbook['uid'] = $uid;
            $newaddressbook['country_id'] = $data['country'];
            $newaddressbook['state_id'] = $data['state'];
            $newaddressbook['city_id'] = $data['city'];
            $newaddressbook['created_by'] = $uid;
            $newaddressbook['updated_by'] = $uid;
            
          // return AddressBook::create($newaddressbook);
           $stored_data= AddressBook::create($newaddressbook);
           $addressbook_data = AddressBook::findOrFail($stored_data['id']);
            if($addressbook_data) {
                $data = array('succ_msg' => "Record Added Successfully.");
                return json_encode($data);
            } 
       }
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
     
    }

    public function getCountries()
    {
        $countries = DB::table('country')->select('id', 'name')->get();
        return $countries;
    }

    public function getStates()
    {
        $states = DB::table('state')->select('id', 'name')->get();
        return $states;
    }
}
