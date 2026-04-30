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
        Schema::table('trainer_achievements', function (Blueprint $table) {
            $table->string('issuer')->after('title');
            $table->string('credential_id')->nullable()->after('issuer');
            $table->string('verification_url')->nullable()->after('credential_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_achievements', function (Blueprint $table) {
            $table->dropColumn(['issuer', 'credential_id', 'verification_url']);
        });
    }
};
