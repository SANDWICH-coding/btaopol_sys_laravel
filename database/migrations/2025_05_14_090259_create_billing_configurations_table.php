<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_configurations', function (Blueprint $table) {
            $table->id('billingConfigId');
            $table->unsignedBigInteger('schoolYearId');
            $table->unsignedBigInteger('yearLevelId');
            $table->unsignedBigInteger('billingItemId');
            $table->decimal('amount', 10, 2);
            $table->boolean('isRequired')->default(1);
            $table->timestamps();

            $table->foreign('schoolYearId')->references('schoolYearId')->on('school_years');
            $table->foreign('yearLevelId')->references('yearLevelId')->on('year_levels');
            $table->foreign('billingItemId')->references('billingItemId')->on('billing_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_configurations');
    }
};
