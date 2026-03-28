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
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->after('id')->index();
            $table->string('button_type')->after('button_text')->nullable();
            
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropForeign('pricing_plans_section_id_foreign');
            $table->dropColumn('section_id');
            $table->dropColumn('button_type');
        });
    }
};
