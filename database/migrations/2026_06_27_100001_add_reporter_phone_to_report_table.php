<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report', function (Blueprint $table) {
            $table->string('reporter_phone', 20)->nullable()->after('tracking_code');
        });
    }

    public function down(): void
    {
        Schema::table('report', function (Blueprint $table) {
            $table->dropColumn('reporter_phone');
        });
    }
};
