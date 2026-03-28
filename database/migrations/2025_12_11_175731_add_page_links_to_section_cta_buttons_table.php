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
        Schema::table('section_cta_buttons', function (Blueprint $table) {
            $table->string('cta_link_type')->after('page_id')->nullable();
            $table->string('custom_url')->after('cta_link_type')->nullable();
            $table->string('section_url')->nullable()->after('custom_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('section_cta_buttons', function (Blueprint $table) {
            $table->dropColumn('section_url');
            $table->dropColumn('custom_url');
            $table->dropColumn('cta_link_type');
        });
    }
};
