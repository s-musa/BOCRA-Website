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
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('title')->nullable();
            $table->longText('details')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->string('cta_button_type')->nullable();
            $table->timestamps();
            
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
