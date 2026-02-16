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
        // Change emi_schedules.amount to decimal(10,2)
        Schema::table('emi_schedules', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        // Change orders.monthly_amount to decimal(10,2)
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('monthly_amount', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to integer
        Schema::table('emi_schedules', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('monthly_amount')->change();
        });
    }
};
