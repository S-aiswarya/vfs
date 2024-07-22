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
        Schema::create('leads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->integer('phone_country_code');
            $table->bigInteger('phone_number');
            $table->integer('alternate_phone_country_code')->nullable();
            $table->bigInteger('alternate_phone_number')->nullable();
            $table->integer('whatsapp_country_code')->nullable();
            $table->bigInteger('whatsapp_number')->nullable();
            $table->string('preferred_course');
            $table->integer('stage_id')->nullable();
            $table->integer('substage_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->integer('agency_id')->nullable();
            $table->bigInteger('assigned_to')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->bigInteger('follow_up_assigned_to')->nullable();
            $table->enum('verification_status', ['Yes', 'No'])->nullable();
            $table->text('note')->nullable();
            $table->string('referrance_from')->nullable();
            $table->bigInteger('student_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('zipcode')->nullable();
            $table->string('state')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('referral_link_id')->nullable();
            $table->boolean('closed')->default(0);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('leads');
    }
};
