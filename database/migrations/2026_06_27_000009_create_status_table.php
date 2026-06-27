<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the STATUS table (report status change history).
     */
    public function up(): void
    {
        Schema::create('status', function (Blueprint $table) {
            $table->increments('status_id');
            $table->unsignedInteger('report_id');
            $table->string('old_status', 50);
            $table->string('new_status', 50);
            $table->timestamp('changed_at')->useCurrent();
            $table->unsignedInteger('changed_by');

            $table->foreign('report_id')
                  ->references('report_id')
                  ->on('report')
                  ->cascadeOnDelete();

            $table->foreign('changed_by')
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
        Schema::dropIfExists('status');
    }
};
