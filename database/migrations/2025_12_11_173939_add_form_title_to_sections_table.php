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
        Schema::table('sections', function (Blueprint $table) {
            $table->string('spa_section_name')->nullable()->after('section_type_id');
            $table->string('spa_section_id')->nullable()->after('spa_section_name');
            $table->string('width_style')->after('section_style')->nullable();
            $table->integer('width')->after('width_style')->nullable();
            $table->string('form_title')->nullable()->after('width')->nullable();
            $table->string('form_sub_title')->nullable()->after('form_title')->nullable();
            $table->string('form_button_text')->nullable()->after('form_sub_title')->nullable();
            $table->string('video_type')->nullable()->after('form_button_text')->nullable();
            $table->longText('video_link')->nullable()->after('video_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('spa_section_name');
            $table->dropColumn('spa_section_id');
            $table->dropColumn('width_style');
            $table->dropColumn('width');
            $table->dropColumn('form_title');
            $table->dropColumn('form_sub_title');
            $table->dropColumn('form_button_text');
        });
    }
};
