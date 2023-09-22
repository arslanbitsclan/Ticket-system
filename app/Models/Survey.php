<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'survey';
    
    public function answers(){
        return $this->hasMany(SurveyAnswer::class, 'survey_uid', 'uid');
    }
}
