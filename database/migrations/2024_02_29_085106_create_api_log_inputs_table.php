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
        Schema::create('api_log_inputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('api_log_id');
            $table->text('input');
            $table->bigInteger('created_by')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_log_inputs');
    }
};
