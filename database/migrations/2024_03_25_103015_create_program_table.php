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
        Schema::create('program', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('department_id');
            $table->integer('pic_id');
            $table->integer('budget')->default(0);
            $table->integer('expense')->default(0);
            $table->integer('disbursement')->default(0);
            $table->integer('financial_id')->default(0);
            $table->integer('staff_lock')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program');
    }
};
