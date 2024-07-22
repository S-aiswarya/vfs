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
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lead_id');
            $table->integer('document_template_id')->nullable();
            $table->string('title');
            $table->text('note')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['Requested', 'Uploaded', 'Accepted', 'Rejected']);
            $table->bigInteger('uploaded_by')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->bigInteger('accepted_by');
            $table->bigInteger('rejected_by');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('accepted_on')->nullable();
            $table->dateTime('rejected_on')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
