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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token')->unique();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('office_id')->nullable();
            $table->text('venue')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->bigInteger('created_by_admin')->nullable();
            $table->bigInteger('updated_by_admin')->nullable();
            $table->bigInteger('created_by_user')->nullable();
            $table->bigInteger('updated_by_user')->nullable();
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
        Schema::dropIfExists('events');
    }
};
