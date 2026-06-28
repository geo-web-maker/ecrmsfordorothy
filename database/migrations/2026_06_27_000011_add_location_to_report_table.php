<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report', function (Blueprint $table) {
            $table->string('location_address', 255)->nullable()->after('description');
            $table->decimal('location_latitude', 10, 8)->nullable()->after('location_address');
            $table->decimal('location_longitude', 11, 8)->nullable()->after('location_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('report', function (Blueprint $table) {
            $table->dropColumn(['location_address', 'location_latitude', 'location_longitude']);
        });
    }
};
