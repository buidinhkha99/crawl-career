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
            $table->string('place_printed')->nullable()->comment('Vị trí cấp thẻ');
            $table->date('complete_from_printed')->nullable()->comment('Ngày bắt đầu huấn luyện');
            $table->date('complete_to_printed')->nullable()->comment('Ngày kết thúc huấn luyện');
            $table->string('director_name_printed')->nullable()->comment('Tên giám đốc/ người chịu trách nhiệm');
            $table->integer('signature_photo_printed')->nullable()->comment('ID ảnh chữ ký');
            $table->date('effective_to_printed')->nullable()->comment('Ngày có hiệu lực');
            $table->string('work_unit_printed')->nullable()->comment('Đơn vị làm việc');
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
            $table->dropColumn('place_printed');
            $table->dropColumn('complete_from_printed');
            $table->dropColumn('complete_to_printed');
            $table->dropColumn('director_name_printed');
            $table->dropColumn('signature_photo_printed');
            $table->dropColumn('effective_to_printed');
            $table->dropColumn('work_unit_printed');
        });
    }
};
