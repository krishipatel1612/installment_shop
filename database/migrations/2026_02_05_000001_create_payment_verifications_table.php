<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('emi_schedule_id');
            $table->unsignedBigInteger('user_id');

            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // manual / cash / upi / bank_transfer / online
            $table->string('transaction_id')->nullable();

            // Status: pending / verified / rejected
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin ID

            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('emi_schedule_id')->references('id')->on('emi_schedules')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['user_id', 'status']);
            $table->index(['emi_schedule_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_verifications');
    }
};
