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
        Schema::create('foods_menu', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('volume')->nullable();
            $table->string('volume_unit')->nullable();
            $table->string('mass')->nullable();
            $table->string('mass_unit')->nullable();
            $table->integer('price');
            $table->tinyInteger('sale')->default(0);
            $table->tinyInteger('stock')->default(0);
            $table->integer('stand_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods_menu');
    }
};
