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
        Schema::create('task_time_metrics', function (Blueprint $table) {
            $table->id();
            $table->integer('initial_estimation')->nullable();
            $table->integer('time_in_progress')->default(0);
            $table->integer('time_in_review')->default(0);
            $table->integer('wakapi_logged_time')->default(0);
            $table->integer('total_actual_time')->default(0);
            $table->boolean('is_overdue')->default(false);
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_time_metrics');
    }
};
