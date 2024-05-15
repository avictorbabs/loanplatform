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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('tuition_fees', 10, 2)->nullable();
            $table->decimal('hostel_fees', 10, 2)->nullable();
            $table->decimal('other_costs', 10, 2)->nullable();
            $table->decimal('cost_of_living', 10, 2)->nullable();
            $table->decimal('total_loan_amount', 10, 2)->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->json('tenure_options')->nullable();
            $table->decimal('payable_interest_vaule', 10, 2)->nullable();
            $table->decimal('monthly_repayment', 10, 2)->nullable();
            $table->decimal('total_repayment', 10, 2)->nullable();
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
