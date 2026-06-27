<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the CRIME table (consolidates old crime_categories + crime fields).
     */
    public function up(): void
    {
        Schema::create('crime', function (Blueprint $table) {
            $table->increments('crime_id');
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->string('location', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('severity_level', ['Low', 'Medium', 'High', 'Critical'])->default('Low');
            $table->date('date_occurred')->nullable();
            $table->enum('status', ['Reported', 'Under Investigation', 'Verified', 'Resolved', 'Closed'])
                  ->default('Reported');
            $table->boolean('is_verified')->default(false);
            $table->unsignedInteger('verified_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('verified_by')
                  ->references('stuff_id')
                  ->on('stuff')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crime');
    }
};
