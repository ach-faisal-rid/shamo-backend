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
        Schema::create('transactions_items', function (Blueprint $table) {
            $table->id();

            $table->integer('usersID'); // relasi ke users table
            $table->integer('productID'); // relasi ke product table
            $table->integer('transactionID'); // relasi ke transaction table

            $table->string('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_items');
    }
};
