<?php

namespace App\Http\Controllers;
use App\Models\Notes;
use App\Models\Quote;
use App\Models\OtpVerification;
use App\Models\NotesHistory;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use DB;
use App\Models\User;
use App\Repositories\EmailRepository;
use App\Repositories\QuoteRepository;

class NotesController extends Controller
{
    protected $tasks; 
    public function __construct(EmailRepository $send_email, QuoteRepository $quote)
    {
       $this->send_email = $send_email;
       $this->quote = $quote;
    }

    public function index(Request $request)
    {
        $notes = new Notes;
        $note_obj = $notes->getNotes($request);
        return $note_obj;
       
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $admin_roles = array('Admin', 'Master Admin', 'CSR');
        $user = JWTAuth::parseToken()->authenticate();
        
        if(isset($data['parent_id'])) {
            $rules = array( 'message' => 'required');
        }
        else {
            $rules = array(
                'assign_to'     => 'required',
                'subject'       => 'required',
                'message'       => 'required'
            );
            $email['type'] = "note-post";
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $data = array('error_msg' => $messages);
            return json_encode($data);
        }
        else
        {
            $stored_data = Notes::create($data);
            $notes_data = Notes::findOrFail($stored_data['id']);
            if($notes_data) {
                $note_history  = $request->only('user_id', 'quote_id', 'created_by');
                $note_history['note_id'] = $notes_data['id'];
                NotesHistory::create($note_history);

                //Quote reject
                if(isset($data['quote_status']) && is_numeric($data['quote_status'])){
                    $quote_message = array('message'=>$data['message'], 'status_id'=>$data['quote_status']);
                    $this->quote->update($quote_message, $data['quote_id']);
                }
                else {
                    //Send email to on note create
                    if($user->hasRole($admin_roles) && $notes_data['note_type'] == 'External') {
                        $quote_user = Quote::find($notes_data['quote_id']);
                        $user_id  = $quote_user['user_id'];  
                        $email['type'] = "note-post-admin";

                        if(isset($data['parent_id']))
                             $email['type'] = "note-reply";
                    }
                    else
                        $user_id = $notes_data['user_id'];
                    $email['user_id'] = $user_id;
                    $email['quote_id'] = $notes_data['quote_id'];
                    $email['message'] = $data['message'];

                    //send SMS
                    $user = User::find($user_id);
                    $email['mobile'] = $user['phonenumber'];
                    $email['date'] = $notes_data['created_at'];
                    $email['service_type'] = $data['service_type'];
                    
                    if(isset($email['type']) && !isset($data['quote_status']) ) {
                        $this->send_email->sendMail($email);
                        $send_sms  = new OtpVerification;
                        $send_sms->sendSMS($email);    
                    }
                }

                $data = array('succ_msg' => "Record Added Successfully.");
                return json_encode($data);
            } 
       }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $note = Notes::where('id', $id)->first();
        $note->status = $request->input('status');
        $note->save();
        if(!$note){
            $data = array('err_msg' => "Note status updated.");
            return $data;
        }
        else{
            $note_history = $data;
            $note_history['note_id'] =  $id;
            NotesHistory::create($note_history);
            $data = array('succ_msg' => "Note status updated.");
            return json_encode($data);
        }
    }

    public function show($id)
    {
        $note = Notes::findOrFail($id);
        return $note;
    }

    public function destroy($id)
    {
      $return_data =Notes::findOrFail($id);
        if ($return_data['parent_id']==0)
       {
          Notes:where('parent_id',$id)->delete($id);
        }
        Notes::destroy($id);
        return  response('Success',200);
    }

    public function getDepartmentList()
    {   
        $departments = DB::table('department')
        ->orderBy('id', 'asc')
        ->get();
        return $departments;
    }

    public function getSubjectList()
    {   
        $subjects = DB::table('subject');
        $user =JWTAuth::parseToken()->authenticate();
        if($user->hasRole('Individual Customer')) {
            $subjects = $subjects->where('display', 3)->orWhere('display', 1);
        }

        $subjects = $subjects->orderBy('id', 'asc')->get();
        return $subjects;
    }
}