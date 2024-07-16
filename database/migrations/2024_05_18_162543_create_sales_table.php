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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('cashier_id');
            $table->tinyInteger('stand_id');
            $table->tinyInteger('menu_id');
            $table->integer('price');
            $table->tinyInteger('amount');
            $table->tinyInteger('discount')->default(0);
            $table->integer('transaction');
            $table->string('customer')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
