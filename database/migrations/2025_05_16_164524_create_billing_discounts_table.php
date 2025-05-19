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
        Schema::create('billing_discounts', function (Blueprint $table) {
            $table->id('billingDiscountId');
            $table->unsignedBigInteger('schoolYearId');
            $table->string('discountName');
            $table->string('discountDiscription')->nullable();
            $table->enum('discountType', ['percentage', 'fixed']);
            $table->decimal('discountValue', 8, 2);
            $table->enum('appliesTo', ['Tuition', 'Miscellaneous', 'Books', 'Registration', 'Others']);
            $table->timestamps();

            $table->foreign('schoolYearId')->references('schoolYearId')->on('school_years')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_discounts');
    }
};
