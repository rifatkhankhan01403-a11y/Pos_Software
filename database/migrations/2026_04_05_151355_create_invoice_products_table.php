<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('invoice_id')
                  ->constrained('invoices')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Product info
            $table->integer('qty');                   // integer for quantity
            $table->decimal('sale_price', 10, 2);     // decimal for price

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_products');
    }
};
