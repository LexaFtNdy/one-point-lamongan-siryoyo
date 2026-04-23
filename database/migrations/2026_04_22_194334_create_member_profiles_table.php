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
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('goal')->nullable();
            $table->string('gender_preference')->nullable();
            $table->string('experience_level')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('injury_history')->nullable();
            $table->string('coaching_style')->nullable();
            $table->string('available_shift')->nullable();
            $table->decimal('max_budget', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
