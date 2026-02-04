<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {

            $table->decimal('interest_rate',5,2)
                  ->default(0)
                  ->after('months');

            $table->decimal('total_amount',10,2)
                  ->after('interest_rate');
        });
    }

    public function down(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            $table->dropColumn(['interest_rate','total_amount']);
        });
    }
};
