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
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lead_id')->nullable();
            $table->string('message_id')->nullable();
            $table->enum('type', ['Gmail', 'Send'])->default('Gmail');
            $table->string('from_address')->nullable();
            $table->string('from_address_original')->nullable();
            $table->string('to_address')->nullable();
            $table->string('cc')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->dateTime('message_date');
            $table->integer('from_university_id')->nullable();
            $table->integer('from_agency_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
    }
};
