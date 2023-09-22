<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ticket_id','name','size','path',
    ];
    
    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
