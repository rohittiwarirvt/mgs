<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use App\Commons\mailer;
use App\Http\Controllers\OtpVerificationController;
use DB;
use GuzzleHttp;
use Response;
use App\Models\User;
use Hash;


class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $tasks){
        $this->tasks = $tasks;
    }


    public function postEmail(Request $request){
        $this->validate($request, ['email' => 'email']);
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });
        if($response == Password::RESET_LINK_SENT){
            $result = array("message" => "We have emailed your password reset link!");
            return json_encode($result);
        }
        else{
            $result['messages'] = "We can't find a user with that e-mail address.";
            return json_encode($result);
        }
    }

    protected function getEmailSubject()
    {
         return isset($this->subject) ? $this->subject : 'Reset your password for MyGharSeva.com account';
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        
        $credentials = $request->only(
           'email', 'password', 'password_confirmation', 'token'
        );
        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                $res = array( "success" => true, "message" => trans($response) );
                return json_encode($res);
            default:
                $res = array( "success" => false, "message" => trans($response) );
                return json_encode($res);
        }
    }


    public function otpPassword(Request $request){
        $data = $request->all();
        $otp_password = str_random(8);
        $api_key = 'Ad151d8cc6a44c12831634253de7c7aa8';
        $to = $data['phonenumber'];
        $request_url = 'http://api-alerts.solutionsinfini.com/v3/?method=sms';
        $message = 'OTP for your login request is '. $otp_password . '. Please use this OTP as your new password to your MygharSeva Account.';

        $client = new GuzzleHttp\Client();
        $api_url = $request_url .'&api_key='.$api_key.'&to='.$to.'&sender=MGSSEV&message='.$message.'&
           format=json&custom=1,2&flash=0';
         $send_sms = $client->request('GET', $api_url);
         $send_sms->getStatusCode();
         $send_sms->getHeader('content-type');
         json_encode($send_sms->getBody());

         $get_user_details = User::where('phonenumber', $to)->first();
         if($get_user_details){
            $get_user_details->password = bcrypt($otp_password);
            $get_user_details->pass_copy = base64_encode($otp_password);
            $result = $get_user_details->save();
            $messages = array('otp_success' => "Your new password has been sent to your number successfully.");
            return json_encode($messages);
        }
        else{
            $messages['otp_fail'] = "Unable to find user. Please enter the correct number.";
            return json_encode($messages);
        }
    }

}
