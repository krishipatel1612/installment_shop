<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emi_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('emi_schedule_id');
            $table->unsignedBigInteger('user_id');

            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('manual'); // manual / cash / upi
            $table->string('transaction_id')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('emi_schedule_id')->references('id')->on('emi_schedules')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['order_id', 'emi_schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emi_payments');
    }
};
