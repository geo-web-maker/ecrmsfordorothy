<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the CASEASSIGNMENT table (officer assignments to reports).
     */
    public function up(): void
    {
        Schema::create('caseassignment', function (Blueprint $table) {
            $table->increments('assignment_id');
            $table->unsignedInteger('report_id');
            $table->unsignedInteger('stuff_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');

            $table->foreign('report_id')
                  ->references('report_id')
                  ->on('report')
                  ->cascadeOnDelete();

            $table->foreign('stuff_id')
                  ->references('stuff_id')
                  ->on('stuff')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caseassignment');
    }
};
