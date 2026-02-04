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
       Schema::create('emi_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id');
    $table->integer('month_no');
    $table->integer('amount');
    $table->string('status')->default('unpaid');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emi_schedules');
    }
};
