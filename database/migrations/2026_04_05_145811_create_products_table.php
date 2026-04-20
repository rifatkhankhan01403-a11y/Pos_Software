<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('subcategory_id')
                  ->nullable()
                  ->constrained('sub_categories') // <--- match your table name exactly
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // Product Info
            $table->string('name', 100);
            $table->string('unit', 50)->nullable();

            // Stock
            $table->integer('quantity');

            // Pricing
            $table->decimal('buy_price', 10, 2);
            $table->decimal('sell_price', 10, 2)->nullable();

            // Extra
            $table->string('note', 255)->nullable();

            // Image
            $table->string('img_url', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
