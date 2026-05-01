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
       Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('firstName', 50);
    $table->string('email', 50)->unique();
    $table->string('mobile', 50);
    $table->string('password', 255);

    // NEW FIELDS
    $table->string('role', 20)->nullable(); // owner, manager, employee
    $table->unsignedBigInteger('shop_id')->nullable();
    $table->string('shop_name', 100)->nullable();
$table->text('login_token')->nullable();
    $table->string('otp', 10)->nullable();
    $table->timestamps();
});
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
