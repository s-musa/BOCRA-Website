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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('secondary_email')->nullable()->after('email');
            $table->string('primary_whatsapp')->nullable()->after('secondary_phone');
            $table->string('secondary_whatsapp')->nullable()->after('primary_whatsapp');
            $table->string('linkedin_profile')->nullable()->after('ig_profile');
            $table->string('pinterest_profile')->nullable()->after('linkedin_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('linkedin_profile');
            $table->dropColumn('secondary_whatsapp');
            $table->dropColumn('primary_whatsapp');
            $table->dropColumn('secondary_email');
        });
    }
};
