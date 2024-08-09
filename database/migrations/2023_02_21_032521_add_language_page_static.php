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
        Schema::table('page_statics', function (Blueprint $table) {
            $table->string('language')->nullable();
            $table->dropUnique(['title']);
            $table->dropUnique(['path']);
            $table->unique(['language', 'path']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_statics', function (Blueprint $table) {
            $table->dropColumn('language');
            $table->unique('title');
            $table->unique('path');
            $table->dropUnique(['language', 'path']);
        });
    }
};
