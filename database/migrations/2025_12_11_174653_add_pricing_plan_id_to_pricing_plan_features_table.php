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
        Schema::table('pricing_plan_features', function (Blueprint $table) {
            $table->unsignedBigInteger('pricing_plan_id')->after('id');
            
            $table->foreign('pricing_plan_id')->references('id')->on('pricing_plans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_plan_features', function (Blueprint $table) {
            $table->dropForeign('pricing_plan_features_pricing_plan_id_foreign');
            
            $table->dropColumn('pricing_plan_id');
        });
    }
};
