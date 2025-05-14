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
        Schema::create('class_arms', function (Blueprint $table) {
            $table->id('classArmId');
            $table->unsignedBigInteger('schoolYearId');
            $table->unsignedBigInteger('yearLevelId');
            $table->string('className');
            $table->timestamps();

            $table->foreign('schoolYearId')->references('schoolYearId')->on('school_years');
            $table->foreign('yearLevelId')->references('yearLevelId')->on('year_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_arms');
    }
};
