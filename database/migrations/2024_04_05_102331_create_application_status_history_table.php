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
        Schema::create('application_status_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('application_id');
            $table->enum('status', ['Created', 'Applied in University', 'University Rejected', 'University Accepted', 'CAS Approved', 'CAS Rejected', 'Visa Applied', 'Visa Approved', 'Visa Rejected', 'University Fee Paid', 'Admission Completed'])->default('Created');
            $table->bigInteger('created_by');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status_history');
    }
};
