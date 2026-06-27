<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the ADMIN profile table linked to STUFF.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stuff_id');
            $table->string('full_name', 255);

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
        Schema::dropIfExists('admin');
    }
};
