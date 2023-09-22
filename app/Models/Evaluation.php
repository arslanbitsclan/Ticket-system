<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'type'
    ];
    
    public function questions(){
        return $this->hasMany(EvaluationQuestion::class);
    }

}
