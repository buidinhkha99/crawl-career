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
        Schema::table('lesson_user', function (Blueprint $table) {
            $table->boolean('is_complete')->default(false);
        });

        Schema::table('question_user', function (Blueprint $table) {
            $table->boolean('is_correct')->default(false);
        });

        Schema::table('lesson_user_question', function (Blueprint $table) {
            $table->boolean('is_correct')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_user', function (Blueprint $table) {
            $table->dropColumn('is_complete');
        });

        Schema::table('question_user', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });

        Schema::table('lesson_user_question', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });
    }
};
