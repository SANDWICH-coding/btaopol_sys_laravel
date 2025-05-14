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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enrollmentId');
            $table->string('enrollmentNumber')->unique();
            $table->unsignedBigInteger('schoolYearId');
            $table->unsignedBigInteger('yearLevelId');
            $table->unsignedBigInteger('classArmId');
            $table->unsignedBigInteger('studentId');
            $table->enum('enrollmentType', ['new', 'transferee', 'old/continuing'])->default('new');
            $table->timestamps();

            $table->foreign('schoolYearId')->references('schoolYearId')->on('school_years');
            $table->foreign('yearLevelId')->references('yearLevelId')->on('year_levels');
            $table->foreign('classArmId')->references('classArmId')->on('class_arms');
            $table->foreign('studentId')->references('studentId')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
