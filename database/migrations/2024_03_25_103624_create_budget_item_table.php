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
        Schema::create('budget_item', function (Blueprint $table) {
            $table->id();
            $table->integer('program_id');
            $table->string('name');
            $table->integer('price');
            $table->integer('qty');
            $table->string('unit');
            $table->integer('total_price');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_item');
    }
};
