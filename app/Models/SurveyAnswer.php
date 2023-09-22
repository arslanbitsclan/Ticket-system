<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'survey_answer';

    public function survey(){
        return $this->belongsTo(Survey::class, 'uid');
    }
    
    public function evaluationQuestion(){
        return $this->belongsTo(EvaluationQuestion::class, 'evaluation_question_id', 'id');
    }
    
    
}
