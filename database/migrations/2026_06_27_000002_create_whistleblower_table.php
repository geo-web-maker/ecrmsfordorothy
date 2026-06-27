<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the WHISTLEBLOWER table linked to STUFF.
     */
    public function up(): void
    {
        Schema::create('whistleblower', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stuff_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone_number', 20)->nullable();
            $table->date('registration_date')->nullable();
            $table->boolean('is_anonymous')->default(false);

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
        Schema::dropIfExists('whistleblower');
    }
};
