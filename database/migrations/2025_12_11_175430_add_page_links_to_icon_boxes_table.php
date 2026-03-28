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
        Schema::table('icon_boxes', function (Blueprint $table) {
            $table->boolean('has_link')->default(false)->after('details');
            $table->string('icon_link_type')->after('has_link')->nullable();
            $table->string('custom_url')->after('icon_link_type')->nullable();
            $table->string('section_url')->nullable()->after('custom_url');
            $table->unsignedBigInteger('page_id')->nullable()->after('section_url');
            
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('icon_boxes', function (Blueprint $table) {
            $table->dropForeign('icon_boxes_page_id_foreign');
            $table->dropColumn('page_id');
            $table->dropColumn('section_url');
            $table->dropColumn('custom_url');
            $table->dropColumn('icon_link_type');
            $table->dropColumn('has_link');
        });
    }
};
