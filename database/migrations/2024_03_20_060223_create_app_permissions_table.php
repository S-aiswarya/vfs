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
        Schema::create('app_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('permission');
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('role_has_app_permissions', function (Blueprint $table) {
            $table->integer('app_permission_id');
            $table->integer('role_id');
            $table->primary(['app_permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_app_permissions');
        Schema::dropIfExists('app_permissions');
    }
};
