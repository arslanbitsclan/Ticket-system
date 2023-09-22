<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'departments';
    protected $fillable = [
        'name',
    ];
    
    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
