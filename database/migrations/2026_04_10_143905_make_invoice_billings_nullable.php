<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_billings', function (Blueprint $table) {

            $table->integer('customer_id')->nullable()->change();
            $table->string('customer_name')->nullable()->change();
            $table->string('customer_mobile')->nullable()->change();

            $table->json('items')->nullable()->change();

            $table->double('subtotal')->nullable()->change();
            $table->double('discount')->nullable()->change();
            $table->double('vat')->nullable()->change();
            $table->double('total')->nullable()->change();

            $table->double('paid')->nullable()->change();
            $table->double('due')->nullable()->change();

            $table->dateTime('invoice_date')->nullable()->change();
            $table->date('due_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Optional rollback (make them required again)
    }
};
