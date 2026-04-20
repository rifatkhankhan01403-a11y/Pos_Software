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

    $table->integer('customer_id');
    $table->string('customer_name');
    $table->string('customer_mobile');

    $table->json('items');

    $table->double('subtotal');
    $table->double('discount')->default(0);
    $table->double('vat')->default(0);
    $table->double('total');

    $table->double('paid')->default(0);
    $table->double('due')->default(0);

    $table->dateTime('invoice_date');
    $table->date('due_date')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice__billings');
    }
};
