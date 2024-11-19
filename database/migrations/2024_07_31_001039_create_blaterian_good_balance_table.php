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
        Schema::create('blaterian_good_balance', function (Blueprint $table) {
            $table->id();
            $table->integer('balance')->default(0);
            $table->integer('expense')->default(0);
            $table->integer('income')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blaterian_good_balance');
    }
};
