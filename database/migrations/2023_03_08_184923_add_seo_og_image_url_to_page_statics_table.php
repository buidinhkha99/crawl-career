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
            $table->string('seo_og_image_url')->after('seo_og_image')->nullable();
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
            $table->dropColumn('seo_og_image_url');
        });
    }
};
