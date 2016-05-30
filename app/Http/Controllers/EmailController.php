<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\TemplateQueue;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use DB;
use JWTAuth;
use Mail;
use Html;
use URL;

class EmailController extends Controller
{


    public function index(){


    }

    public function store(Request $request){
    	$rules = array(
        'template_body'  => 'required',
        'subject'    => 'required',
        'type' => 'required',
    	);
	    $validator = Validator::make($request->all(), $rules);
	    if ($validator->fails()) {

       		// get the error messages from the validator
        	$messages = $validator->messages();

        	// redirect our user back to the form with the errors from the validator
 		$data = array('error_msg' => $messages);
        return json_encode($data);

    	} else {

    		$newEmailTemplate = $request->all();
            $data   =   EmailTemplate::create($newEmailTemplate);
            $data['succ_msg']   =   "Email Template Added Successfully.";
            return json_encode($data);
	    }
    }



    public function update(Request $request, $id){


    }

    public function getEmailTemplates(){
    	$templates = DB::table('email_templates')->get();
    	return json_encode($templates);
    }

    public function sendEmail(Request $request){
        $data =  $request->all();
        $user_email = $data['email'];
        $template_details = DB::table('email_templates')->where('type', $data['type'])->get();
        foreach($template_details as $template){
            $template_body =  $template->template_body;
            switch($data['type']){
                case 'enquiry':
                $template_body = str_replace("{{customername}}", $data['customername'], $template_body);
                $template_body = str_replace("{{email}}", $data['email'], $template_body);
                $template_body = str_replace("{{customerphone}}", $data['customerphone'], $template_body);
                break;

                case 'note-post':
                $template_body = str_replace("{{username}}", $data['username'], $template_body);
                break;

                case 'registration':
                $template_body = str_replace("{{username}}", $data['username'], $template_body);
                $template_body = str_replace("{{password}}", $data['password'], $template_body);
                $template_body = str_replace("{{firstname}}", $data['firstname'], $template_body);
                break;

                case 'subscription':
                $url_to_unsubscribe = URL::to('/#/unsubscribe', $user_email);
                $reset_password_link = "<a href = $url_to_unsubscribe> Unsubscribe</a>";
                $template_body = str_replace("{{unsubscribe}}", $reset_password_link, $template_body);
                break;
                
                default:
                ##code
                break;
            }
            $template_subject = $template->subject;
            $template_id = $template->id;
        }

        $data =  array(
                'user_email' => $user_email,
                'email_body' => $template_body,
                'email_subject' => $template_subject,
                'email_from' => 'support@mygharseva.com',
                );

        Mail::send('auth.mails', $data, function($message) use($data){
                $message->subject($data['email_subject'], 'Subject');
                $message->to($data['user_email'], 'user');
                $message->from($data['email_from'], 'MygharTeam');
                });
        $template_queue = new TemplateQueue();
        if(isset($data['userid'])){
            $user_id = $data['userid'];
            $template_queue->user_id = $user_id;
        }
        else{
           $template_queue->user_id = 0; 
        }
        $template_queue->template_id = $template_id;
        $template_queue->to = $data['user_email'];
        $template_queue->from = $data['email_from'];
        $template_queue->template_data = $template_body;
        $template_queue->save();
        $data = array('succ_msg' => 'Email sent successfully');
        return $data;

    }
}
