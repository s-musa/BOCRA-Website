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
        Schema::create('customisations', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color');
            $table->string('primary_color_rgb')->nullable();
            $table->string('primary_color_light')->nullable();
            $table->string('primary_color_light_rgb')->nullable();
            $table->string('secondary_color');
            $table->string('secondary_color_rgb')->nullable();
            $table->string('secondary_color_light')->nullable();
            $table->string('secondary_color_light_rgb')->nullable();
            $table->string('button_style')->nullable();
            $table->string('header_style')->default('default');
            $table->string('header_bg')->default('default');
            $table->boolean('top_header')->default(false);
            $table->string('top_header_bg')->nullable();
            $table->string('footer_style')->nullable();
            $table->boolean('footer_has_background')->default(false);
            $table->string('footer_bg_type')->nullable();
            $table->string('footer_bg_color')->nullable();
            $table->longText('footer_text')->nullable();
            $table->string('banner_style')->nullable();
            $table->string('banner_bg')->nullable();
            $table->string('section_width_style')->nullable();
            $table->integer('section_width')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customisations');
    }
};
