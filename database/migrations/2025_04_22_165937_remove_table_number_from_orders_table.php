<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('table_number');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('table_number')->nullable(); // solo por si necesitas revertir
        });
    }
};
