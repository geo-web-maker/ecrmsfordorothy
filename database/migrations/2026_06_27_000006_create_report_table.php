<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the REPORT table linked to STUFF (submitter) and CRIME.
     */
    public function up(): void
    {
        Schema::create('report', function (Blueprint $table) {
            $table->increments('report_id');
            $table->unsignedInteger('stuff_id')->nullable();
            $table->unsignedInteger('crime_id');
            $table->text('description');
            $table->enum('status', ['Submitted', 'Under Review', 'Assigned', 'Resolved', 'Closed'])
                  ->default('Submitted');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->string('tracking_code', 20)->unique()->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('stuff_id')
                  ->references('stuff_id')
                  ->on('stuff')
                  ->nullOnDelete();

            $table->foreign('crime_id')
                  ->references('crime_id')
                  ->on('crime')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
