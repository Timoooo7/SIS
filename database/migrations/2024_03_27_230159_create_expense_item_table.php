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
        Schema::create('expense_item', function (Blueprint $table) {
            $table->id();
            $table->integer('program_id');
            $table->string('name');
            $table->integer('price');
            $table->integer('qty');
            $table->string('unit');
            $table->integer('total_price');
            $table->integer('financial_id')->nullable();
            $table->string('reciept')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_item');
    }
};
