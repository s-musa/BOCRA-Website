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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_plan_feature_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->string('tagline')->nullable();
            $table->longText('description')->nullable();
            $table->bigInteger('price')->default(0);
            $table->enum('billing_period', ['monthly', 'yearly', 'one-time', 'custom'])->default('monthly');
            $table->string('billing_period_label')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('order')->default(0);
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->timestamps();
            
            $table->foreign('pricing_plan_feature_id')->references('id')->on('pricing_plan_features');
            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
