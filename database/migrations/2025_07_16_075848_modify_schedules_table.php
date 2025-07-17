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

        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('date', 'start_date');
            $table->renameColumn('time', 'start_time');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->after('subject_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->string('title', 128)->nullable()->after('subject_id');
            $table->text('description')->after('title');
            $table->date('end_date')->after('start_time');
            $table->time('end_time')->after('end_date');
            $table->dropColumn('day');
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('start_date', 'date');
            $table->renameColumn('start_time', 'time');

            $table->dropColumn(['title', 'description', 'end_date', 'end_time']);

            $table->string('day')->nullable();
        });
    }
};
