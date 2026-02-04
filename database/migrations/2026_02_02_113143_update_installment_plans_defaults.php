<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('installment_plans', function (Blueprint $table) {
        $table->decimal('interest_rate',5,2)
              ->default(0)
              ->change();

        $table->decimal('total_amount',10,2)
              ->default(0)
              ->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
