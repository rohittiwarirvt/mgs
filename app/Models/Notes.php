<?php

namespace App;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';
    protected $fillable = [
        'user_id', 'quote_id', 'department_id', 'subject_id', 'parent_id', 'message', 'note_type', 'status', 'created_by' 
    ];


    public function getNotes($filter)
    {
        $notes = Notes::select();
        if(isset($filter['quote_id'])) {
           $notes = $notes->where('quote_id', $filter['quote_id']);
        }
        $notes = $notes->orderBy('id', 'asc')->get();

        foreach($notes as $key=>$note)
        {    
            $user_info = $this->getUserInfo($note->user_id);
            $notes[$key]->created_for = $user_info->name;
            $user = $this->getUserInfo($note->created_by);
            $notes[$key]->created_by = $user->name;  
        }
        return $notes;
    }

    public function getLastNotes($quote_id)
    { 
        $notes = Notes::select();
        if($quote_id) {
           $notes = $notes->where('quote_id', $quote_id);
        }
        $notes = $notes->orderBy('id', 'desc')->first();
        return $notes;
    }

    public function getUserInfo($id)
    {
       $user = User::find($id);
        if(!$user->first_name && !$user->last_name)
            $user->name = ucfirst($user->username);
       else
            $user->name = ucfirst($user->first_name) . " " . ucfirst($user->last_name);
       return $user;
    } 

}

