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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_id')->nullable()->change();
            $table->string('type')->default(\App\Enums\QuizType::Exam);
            $table->float('score_pass_quiz')->nullable();
            $table->integer('question_amount_quiz')->default(20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_id')->change();
            $table->dropColumn('type');
            $table->dropColumn('score_pass_quiz');
            $table->dropColumn('question_amount_quiz');
        });
    }
};
