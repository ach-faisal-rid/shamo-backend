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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('usersID'); // relasi ke product table

            $table->string('address')->nullable();

            $table->float('shipping_price')->default(0);
            $table->float('total_price')->default(0);

            $table->string('payment')->default('MANUAL');
            $table->string('status')->default('PENDING');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
