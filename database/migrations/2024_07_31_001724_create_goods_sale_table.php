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
        Schema::create('goods_sale', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction')->default(0);
            $table->string('customer');
            $table->integer('operational_id')->nullable();
            $table->integer('cashier_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_sale');
    }
};
