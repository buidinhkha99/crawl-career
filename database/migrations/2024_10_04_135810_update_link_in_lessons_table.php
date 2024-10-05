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
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('link')->nullable()->comment('Link driver for show content lessons');
            $table->string('document_type')->default(\App\Enums\LessonConstant::LESSON_TYPE_NORMAL_TEXT)->comment('Type content lessons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('link');
            $table->dropColumn('document_type');
        });
    }
};
