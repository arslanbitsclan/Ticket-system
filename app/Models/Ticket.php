<?php

namespace App\Models;

use App\Models\Type;
use App\Models\User;
use App\Models\Status;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Attachment;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = [];
    
    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function priority(){
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }
    
        public function status(){
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function ticketType(){
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function contact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function assignedTo(){
        return $this->belongsTo(User::class, 'assigned_to');
    }

}
