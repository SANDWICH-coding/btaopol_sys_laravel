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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('paymentId');

            $table->unsignedBigInteger('enrollmentId');
            $table->date('paymentDate');
            $table->string('receiptNumber');
            $table->unsignedBigInteger('billingConfigId');
            $table->decimal('amountPaid', 10, 2);
            $table->string('paymentMethod')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('enrollmentId')->references('enrollmentId')->on('enrollments')->onDelete('cascade');
            $table->foreign('billingConfigId')->references('billingConfigId')->on('billing_configurations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
