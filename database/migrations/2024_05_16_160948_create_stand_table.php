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
        Schema::create('stand', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('place')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('pic_id');
            $table->integer('expense')->nullable();
            $table->integer('income')->nullable();
            $table->integer('profit')->nullable();
            $table->integer('balance')->nullable();
            $table->tinyInteger('menu_lock')->default(0);
            $table->tinyInteger('sale_validation')->default(0);
            $table->integer('cashier_token')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stand');
    }
};
