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
        Schema::create('referral_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('token');
            $table->integer('lead_source_id');
            $table->integer('event_id')->nullable();
            $table->integer('agency_id')->nullable();
            $table->date('last_date_of_validity')->nullable();
            $table->text('top_description')->nullable();
            $table->text('bottom_description')->nullable();
            $table->text('private_remarks')->nullable();
            $table->string('banner_image')->nullable();
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
        Schema::dropIfExists('referral_links');
    }
};
