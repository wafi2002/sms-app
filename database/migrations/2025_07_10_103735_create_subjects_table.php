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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unsignedBigInteger('lecturer_id');
            $table->foreign('lecturer_id')->references('id')->on('lecturers')->onDelete('cascade');
            $table->string('subject_code', 8)->unique();
            $table->string('subject_name', 64)->unique();
            $table->integer('credit_hours')->default(3);
            $table->unsignedBigInteger('prereq_sub_id')->nullable();
            $table->foreign('prereq_sub_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->string('class_location', 64);
            $table->string('class_department',32);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
