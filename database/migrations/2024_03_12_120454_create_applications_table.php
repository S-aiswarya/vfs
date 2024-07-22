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
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->bigInteger('country_id');
            $table->bigInteger('university_id');
            $table->bigInteger('intake_id');
            $table->bigInteger('course_level_id');
            $table->bigInteger('subject_area_id');
            $table->string('course');
            $table->string('application_number')->nullable();
            $table->string('acceptance_letter')->nullable();
            $table->string('cas_document')->nullable();
            $table->string('fee_receipt')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['Created', 'Applied in University', 'University Rejected', 'University Accepted', 'CAS Approved', 'CAS Rejected', 'Visa Applied', 'Visa Approved', 'Visa Rejected', 'University Fee Paid', 'Admission Completed'])->default('Created');
            $table->bigInteger('status_updated_by');
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
