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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('banner_style')->after('description')->nullable();
            $table->string('banner_background_type')->after('banner_style')->nullable();
            $table->string('banner_background_color')->after('banner_background_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('banner_background_color');
            $table->dropColumn('banner_background_type');
            $table->dropColumn('banner_style');
        });
    }
};
