<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class ContactController extends Controller
{
    
   
    public function index()
    {
      
    }

    public function store(Request $request)
    {
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email',
            'subject'    => 'required',
            'description'=> 'required',
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
            $contact = $request->all();
            Contact::create($contact);
            $data['succ_msg']   =   "Thanks for Contacting Us. We will get back to you";
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
