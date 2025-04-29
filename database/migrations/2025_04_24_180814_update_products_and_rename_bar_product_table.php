<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Eliminar columna 'price' de la tabla products
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
        });

        // Renombrar la tabla bar_product a bar_products
        if (Schema::hasTable('bar_product')) {
            Schema::rename('bar_product', 'bar_products');
        }
    }

    public function down(): void
    {
        // Volver a agregar 'price' en caso de rollback
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable();
        });

        // Volver a renombrar la tabla a su nombre original
        if (Schema::hasTable('bar_products')) {
            Schema::rename('bar_products', 'bar_product');
        }
    }
};