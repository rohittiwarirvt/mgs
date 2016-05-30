<?php

namespace App;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotesHistory extends Model
{
    protected $table = 'notes_history';
    protected $fillable = [
        'user_id', 'note_id', 'quote_id', 'status', 'parent_id', 'created_by' 
    ];

}
