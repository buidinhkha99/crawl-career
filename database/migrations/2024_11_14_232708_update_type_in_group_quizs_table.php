<?php

use App\Enums\ScopeAccountType;
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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('scope_type')->default(ScopeAccountType::OCCUPATIONAL)->comment('Loại phạm vi áp dụng: 1. OCCUPATIONAL: An toan, CAREER: Nghề, 3.LEVEL: Thi nâng bậc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('scope_type');
        });
    }
};
