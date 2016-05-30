<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use GuzzleHttp;
use Response;



class OtpVerificationController extends Controller {


     public function __construct(UserRepository $tasks){
        $this->tasks = $tasks;
    }


    /**
     * Send back all comments as JSON
     *
     * @return Response
     */
    public function index()
    {
        return Response::json(OtpVerification::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $to = $request->phonenumber;
        $otp = mt_rand(1000, 9999);
        $api_key = 'Ad151d8cc6a44c12831634253de7c7aa8';
        $request_url = 'http://api-alerts.solutionsinfini.com/v3/?method=sms';
        $message = 'OTP for your login request is '. $otp . '. Please enter this OTP to verify your identity on MygharSeva';

        $client = new GuzzleHttp\Client();
        $api_url = $request_url .'&api_key='.$api_key.'&to='.$to.'&sender=MGSSEV&message='.$message.'&
           format=json&custom=1,2&flash=0';
         $send_sms = $client->request('GET', $api_url);
         $send_sms->getStatusCode();
         $send_sms->getHeader('content-type');
         json_encode($send_sms->getBody());

        /* save sms data*/
        $otpdata['receiver'] = $to;
        $otpdata['sender']   = 'MYGHARSEVA';
        $otpdata['sms_type'] = 'otp';
        $otpdata['sms_body'] = $otp;
        $otpdata['status']   = 1;

        $otp_data  = OtpVerification::create($otpdata);
        if(!empty($otp_data))
           $data = array('success' => "OTP has been sent to your mobile number $to.");
        else
           $data = array('error' => "Please regenerate OTP."); 
        return json_encode($data);
       }

       public function checkotp(Request $request)
       {
            $otp_value = $request->otp;
            $userid = $request->id;
            $mobile = $request->items;

            $otpdata = DB::table('message_directory')
                    ->select('receiver', 'sender')
                    ->where('sms_body', '=', $otp_value)
                    ->where('sms_type', '=', 'otp')
                    ->where('receiver', '=', $mobile)
                    ->get();

            if (!empty($otpdata)){
                $this->statusUpdate($otp_value, $mobile);
               $user =  $this->tasks->update(array ('status'=>'1'), $userid);
               return Response::json(array('success' => 'Congratulations! Your account is activated successfully.'));
            }else{
                return Response::json(array('error' => 'Please enter valid one time password.'));
            }
       }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        OtpVerification::destroy($id);

        return Response::json(array('success' => true));
    }

    function statusUpdate($otp_value, $mobile){
      DB::table('message_directory')
        ->where('sms_body', '=', $otp_value)
        ->where('sms_type', '=', 'otp')
        ->where('receiver', '=', $mobile)
        ->update(['status' => 0]);
        return Response::json(array('success' => true));
    }
}
