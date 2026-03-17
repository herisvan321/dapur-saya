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
        Schema::table('banners', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('offer_text');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('name');
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
