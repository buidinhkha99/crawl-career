<?php

use App\Models\Customization;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Customization::where('type', 'HTML')->update([
            'type' => 'Body Script',
        ]);

        Customization::create([
            'type' => 'Header Script',
            'content' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Customization::where('type', 'Body Script')->update([
            'type' => 'HTML',
        ]);

        Customization::where('type', 'Header Script')->delete();
    }
};
