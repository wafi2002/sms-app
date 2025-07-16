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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_code',8)->unique()->nullable();
            $table->string('class_name',32)->nullable();
            $table->text('class_location')->nullable();
            $table->string('class_department',32)->nullable();
            $table->unsignedBigInteger('class_type')->nullable()->comment('1=Lab, 2=Lecture, 3=Studio, 4=Simulation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
