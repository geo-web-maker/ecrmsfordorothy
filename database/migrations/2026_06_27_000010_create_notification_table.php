<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the NOTIFICATION table.
     */
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('notification_id');
            $table->unsignedInteger('report_id');
            $table->unsignedInteger('stuff_id')->nullable();
            $table->text('message');
            $table->timestamp('sent_at')->useCurrent();
            $table->enum('channel', ['SMS', 'email', 'in-app'])->default('in-app');
            $table->enum('delivery_status', ['pending', 'sent', 'failed', 'read'])->default('pending');

            $table->foreign('report_id')
                  ->references('report_id')
                  ->on('report')
                  ->cascadeOnDelete();

            $table->foreign('stuff_id')
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
        Schema::dropIfExists('notification');
    }
};
