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
        Schema::create('school_year_level', function (Blueprint $table) {
            $table->id('schoolYearLevelId');
            $table->unsignedBigInteger('schoolYearId');
            $table->unsignedBigInteger('yearLevelId');
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
        Schema::dropIfExists('school_year_level');
    }
};
