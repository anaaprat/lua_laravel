<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bar_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->float('price');
            $table->integer('stock')->default(0);
            $table->boolean('available')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'product_id']); // Evita duplicados del mismo producto en el mismo bar
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bar_product');
    }
};
