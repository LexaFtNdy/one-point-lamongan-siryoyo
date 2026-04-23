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
        Schema::create('trainer_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_profile_id')->constrained('trainer_profiles')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('1=Senin, 7=Minggu');
            $table->enum('shift', ['morning', 'afternoon', 'evening']);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_schedules');
    }
};
