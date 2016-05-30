<?php

namespace App\Http\Controllers;
use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;



class PartnerController extends Controller
{
  
	 public function index()
    {
      
    }

    public function store(Request $request)
    {
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email',
            'service_name' => 'required',
            'comment'	 => 'required',
            'phonenumber'=> 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            $data = array('error_msg' => $messages);
            return json_encode($data);
        }
        else
        {
            $partner = $request->all();
            Partner::create($partner);
            $data['succ_msg']   =   "Thank You! We are glad to receive your partnership request.
            Our representative will call you back to spotlight the excellent work opportunity with MyGharSeva. 
            Looking forward to see you onboard with us.
            Team-MyGharSeva.";
            return json_encode($data);

        }
    }
       public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
     
    }

}
