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
        Schema::create('feature_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_plan_feature_id');
            $table->unsignedBigInteger('pricing_plan_id');
            $table->timestamps();
            
            $table->foreign('pricing_plan_feature_id')->references('id')->on('pricing_plan_features');
            $table->foreign('pricing_plan_id')->references('id')->on('pricing_plans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_plans');
    }
};
