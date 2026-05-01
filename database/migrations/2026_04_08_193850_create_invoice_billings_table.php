<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_billings', function (Blueprint $table) {
    $table->id();

    $table->integer('customer_id')->nullable();
    $table->string('customer_name')->nullable();
    $table->string('customer_mobile')->nullable();

    $table->json('items')->nullable();

    $table->decimal('subtotal', 10, 2)->nullable();
    $table->decimal('discount', 10, 2)->nullable();
    $table->decimal('vat', 10, 2)->nullable();
    $table->decimal('total', 10, 2)->nullable();

    $table->decimal('paid', 10, 2)->nullable();
    $table->decimal('due', 10, 2)->nullable();

    $table->decimal('profit', 10, 2)->nullable()->default(0);

    $table->dateTime('invoice_date')->nullable();
    $table->date('due_date')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                 $table->text('note')->nullable();
                   $table->unsignedBigInteger('shop_id')->nullable();
                    $table->string('courier')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_billings');
    }
};
