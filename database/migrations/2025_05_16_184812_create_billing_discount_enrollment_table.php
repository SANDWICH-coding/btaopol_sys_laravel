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
        Schema::create('billing_discount_enrollment', function (Blueprint $table) {
            $table->id('billingDiscEnrollId');
            $table->unsignedBigInteger('enrollmentId');
            $table->unsignedBigInteger('billingDiscountId');
            $table->timestamps();

            $table->foreign('enrollmentId')->references('enrollmentId')->on('enrollments')->onDelete('cascade');
            $table->foreign('billingDiscountId')->references('billingDiscountId')->on('billing_discounts')->onDelete('cascade');

            $table->unique(['enrollmentId', 'billingDiscountId'], 'enroll_disc_unique');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_discount_enrollment');
    }
};
