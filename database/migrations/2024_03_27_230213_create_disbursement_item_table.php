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
        Schema::create('disbursement_item', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('program_id');
            $table->integer('letter_id');
            $table->integer('financial_id');
            $table->integer('price');
            $table->string('reciept');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_item');
    }
};
