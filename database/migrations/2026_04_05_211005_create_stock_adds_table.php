<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('stock_adds', function (Blueprint $table) {
        $table->id();

        // invoice>unique();
        $table->string('invoice_no')->nullable();

        // supplier (one supplier per purchase)
        $table->unsignedBigInteger('supplier_id')->nullable();
        $table->string('supplier_name')->nullable();
        $table->string('supplier_phone')->nullable();
        $table->text('supplier_address')->nullable();

        // date
        $table->date('purchase_date')->nullable();

        // 🔥 ALL PRODUCTS HERE (MULTIPLE)
        $table->json('items')->nullable();

        // 🔥 ALL DUE INSTALLMENTS HERE (MULTIPLE)
        $table->json('due_plan')->nullable();

        // summary
        $table->integer('total_qty')->default(0);
        $table->decimal('total_cost', 10, 2)->default(0);
        $table->decimal('paid_amount', 10, 2)->default(0);
        $table->decimal('due_amount', 10, 2)->default(0);

        // optional note
        $table->text('note')->nullable();
  $table->unsignedBigInteger('user_id')->nullable();
   $table->unsignedBigInteger('shop_id')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adds');
    }
};
