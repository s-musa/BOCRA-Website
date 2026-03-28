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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('title');
            $table->string('type');     // e.g page, custom, service
            $table->string('url')->nullable();
            $table->boolean('has_children')->default(false);
            $table->string('child_type')->nullable();
            $table->string('component')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('menus');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
            
            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
