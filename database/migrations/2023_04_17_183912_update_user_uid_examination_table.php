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
        Schema::table('examinations', function (Blueprint $table) {
            $table->dateTime('dob')->nullable();
            $table->string('username')->nullable();
            $table->string('gender')->nullable();
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('group')->nullable();
            $table->string('department')->nullable();
            $table->string('factory_name')->nullable();
            $table->uuid('uuid');
            $table->string('exam_name')->nullable();
            $table->string('quiz_name')->nullable();
            $table->dateTime('start_time_exam')->nullable();
            $table->dateTime('end_time_exam')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('username');
            $table->dropColumn('gender');
            $table->dropColumn('name');
            $table->dropColumn('position');
            $table->dropColumn('department');
            $table->dropColumn('group');
            $table->dropColumn('factory_name');
            $table->dropColumn('uuid');
            $table->dropColumn('exam_name');
            $table->dropColumn('quiz_name');
            $table->dropColumn('start_time_exam');
            $table->dropColumn('end_time_exam');
        });
    }
};
