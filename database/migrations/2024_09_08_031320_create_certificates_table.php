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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type')->comment('Loại thẻ occupational-safety (an toàn lao động), electrical-safety (an toàn điện)');
            $table->integer('card_id')->comment('Mã số thẻ, theo số thứ tự thẻ, làm mới theo năm');
            $table->json('card_info')->comment('Thông tin của thẻ tại thời điểm cấp');
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
        Schema::dropIfExists('certificates');
    }
};
