<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Http\Requests;
use DB;
use App\Models\User;
use App\Http\Controllers\Controller;
use Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;


class SubscriptionController extends Controller
{
   
    
   public function index(){

  
    }

    public function subscribe(Request $request, $id)
    {
        $users = DB::table('users')->lists('email');
        $emails = DB::table('subscription')->lists('email');
        $email = $request->input('email');
        $uuid = str_random(40);
        $subscription  = new Subscription();
        if(in_array($email, $emails)){
            $data = array('error_msg' => 'You are already subscribed');
            return json_encode($data);
        }
        else{
            if(in_array($email, $users)){
            $user_details = User::findOrFail($id);
            $subscription->user_id =  $user_details->id;
            $subscription->source = "profile";
            $subscription->token = $uuid;
            $subscription->status = "subscribed";
            $subscription->email = $user_details->email;
            $subscription->save();
            }
            else{
            $user_id = 0;
            $subscription->user_id = $user_id;
            $subscription->token = $uuid;
            $subscription->source = "footer";
            $subscription->email = $request->input('email');
            $subscription->status = "subscribed";
            $subscription->save();
            }
        }
        $data['succ_msg'] = "You have been successfully subscribed";
        return json_encode($data);
    }   

    public function unsubscribe(Request $request){
         $rules = array(
            
            'email'      => 'required|email'
           
       );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            $data = array('error_msg' => $messages);
            return json_encode($data);
        }


        else{

            $request_options = $request->only('email');
            $subscription  = new Subscription();
            $subscription_history = new SubscriptionHistory();
            $subscription_details = Subscription::where('email', $request_options['email'])->first();
            $subscription_history->email = $subscription_details['email'];
            $subscription_history->source = $subscription_details['source'];
            $subscription_history->subscription_id = $subscription_details['id'];
            $subscription_history->created_at = $subscription_details['created_at'];
            $subscription_history->updated_at = $subscription_details['updated_at'];
            $subscription_history->save();
            $subscription_details->status = "unsubscribed";
            $subscription_details->save();
            $data['succ_msg']  = "You have successfully unsubscribed";
            return json_encode($data);
            }

        }
}
