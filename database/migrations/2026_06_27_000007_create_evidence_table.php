<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the EVIDENCE table (sub-table of REPORT).
     */
    public function up(): void
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->increments('evidence_id');
            $table->unsignedInteger('report_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->enum('file_type', ['image', 'video', 'audio', 'document']);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('report_id')
                  ->references('report_id')
                  ->on('report')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};
