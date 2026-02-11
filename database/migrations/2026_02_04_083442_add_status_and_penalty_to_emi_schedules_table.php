<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emi_schedules', function (Blueprint $table) {

            if (!Schema::hasColumn('emi_schedules', 'due_date')) {
                $table->date('due_date')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('emi_schedules', 'status')) {
                $table->string('status')->default('pending')->after('due_date');
                // pending / paid / overdue
            }

            if (!Schema::hasColumn('emi_schedules', 'penalty_amount')) {
                $table->decimal('penalty_amount', 12, 2)->default(0)->after('status');
            }

            if (!Schema::hasColumn('emi_schedules', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('penalty_amount');
            }

        });
    }

    public function down(): void
    {
        Schema::table('emi_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('emi_schedules', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('emi_schedules', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('emi_schedules', 'penalty_amount')) {
                $table->dropColumn('penalty_amount');
            }
            if (Schema::hasColumn('emi_schedules', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
