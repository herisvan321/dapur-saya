<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('icon_url');
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });
    }
};
