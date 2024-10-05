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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('image_font')->nullable();
            $table->string('image_font_url')->nullable();
            $table->string('image_back')->nullable();
            $table->string('image_back_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('image_font');
            $table->dropColumn('image_font_url');
            $table->dropColumn('image_back');
            $table->dropColumn('image_back_url');
        });
    }
};
