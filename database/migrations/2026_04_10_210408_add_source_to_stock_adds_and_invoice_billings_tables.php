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
        Schema::table('stock_adds', function (Blueprint $table) {
            $table->string('source')->nullable()->after('purchase_date');
        });

        Schema::table('invoice_billings', function (Blueprint $table) {
            $table->string('source')->nullable()->after('customer_mobile');
        });
    }

    public function down(): void
    {
        Schema::table('stock_adds', function (Blueprint $table) {
            $table->dropColumn('source');
        });

        Schema::table('invoice_billings', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
