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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->jsonb('cover')->nullable();
            $table->jsonb('thumbnail')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->default(\App\Enums\PostStatus::Draft);
            $table->boolean('featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
