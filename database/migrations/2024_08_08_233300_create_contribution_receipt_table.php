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
        Schema::create('contribution_receipt', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('contribution_id');
            $table->smallInteger('financial_id')->nullable();
            $table->smallInteger('months');
            $table->string('receipt');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_receipt');
    }
};
