<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();

            // Usuario que recarga
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Bar donde se hace la recarga (usuario con role = 'bar')
            $table->foreignId('bar_id')->constrained('users')->onDelete('cascade');

            $table->float('amount'); // Cantidad recargada

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};

