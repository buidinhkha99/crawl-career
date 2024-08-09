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
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('fulfilled')->default(false);
        });

        Schema::table('examinations', function (Blueprint $table) {
            $table->string('state')->default(\App\Enums\ExaminationStatus::NoExam)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('fulfilled');
        });

        Schema::table('examinations', function (Blueprint $table) {
            $table->string('state')->nullable()->change();
        });
    }
};
