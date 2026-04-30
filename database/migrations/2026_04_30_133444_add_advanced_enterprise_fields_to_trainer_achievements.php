<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure all existing null achieved_at are set to something to avoid error when modifying column
        DB::table('trainer_achievements')->whereNull('achieved_at')->update(['achieved_at' => now()->toDateString()]);

        Schema::table('trainer_achievements', function (Blueprint $table) {
            // Modify achieved_at to be not nullable
            $table->date('achieved_at')->nullable(false)->change();
            
            // Add category and expires_at
            $table->enum('category', ['certification', 'award', 'education', 'license', 'seminar', 'other'])->default('certification')->after('trainer_profile_id');
            $table->date('expires_at')->nullable()->after('achieved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_achievements', function (Blueprint $table) {
            $table->date('achieved_at')->nullable()->change();
            $table->dropColumn(['category', 'expires_at']);
        });
    }
};
