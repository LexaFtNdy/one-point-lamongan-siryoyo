<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN `shift` VARCHAR(20) NOT NULL DEFAULT 'Pagi'");
        DB::statement("ALTER TABLE trainer_schedules MODIFY COLUMN `shift` VARCHAR(20) NOT NULL DEFAULT 'Pagi'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN `shift` ENUM('morning','afternoon','evening') NOT NULL DEFAULT 'morning'");
        DB::statement("ALTER TABLE trainer_schedules MODIFY COLUMN `shift` ENUM('morning','afternoon','evening') NOT NULL DEFAULT 'morning'");
    }
};
