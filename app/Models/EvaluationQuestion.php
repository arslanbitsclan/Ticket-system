<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'evaluation_id', 'question'
    ];
    protected $table = 'evaluation_questions';

    public function evaluation(){
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

}
