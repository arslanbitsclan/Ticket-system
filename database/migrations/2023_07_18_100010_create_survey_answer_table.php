<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('survey_answer')) { return; }
        Schema::create('survey_answer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('survey_uid', 30)->nullable()->index();
            $table->string('evaluation_question_id', 250)->index();
            $table->text('answer')->nullable()->index();
            $table->integer('score')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_answer');
    }
};
