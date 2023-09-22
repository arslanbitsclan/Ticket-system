<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evaluation = Evaluation::create(['type' => 'Opening & product knowledge (60%)']);
        EvaluationQuestion::insert([
            ['evaluation_id' => $evaluation->id, 'question' => 'Greetings', 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Attentive' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Customer Preffered Language' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Correct and relevant information' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Apology if necessary' , 'created_at' => now()]
        ]);
        
        $evaluation = Evaluation::create(['type' => 'Resolution & Colsure (40%)']);
        EvaluationQuestion::insert([
            ['evaluation_id' => $evaluation->id, 'question' => 'Chat handling' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Resloved issue (if any)' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Closure' , 'created_at' => now()]
        ]);
        
        $evaluation = Evaluation::create(['type' => 'Non-negotiables']);
        EvaluationQuestion::insert([
            ['evaluation_id' => $evaluation->id, 'question' => 'Not Prompt (Response time)' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Did not offer alternatives / Educating customer for future problem prevention / Cross Sell' , 'created_at' => now()],
            ['evaluation_id' => $evaluation->id, 'question' => 'Previous conversation not read (when required)/ robotic feel' , 'created_at' => now()]
        ]);
    }
}
