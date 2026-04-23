<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('message')->nullable()->after('amount');
        });

        // Change status to VARCHAR to easily add 'canceled' without dealing with ENUM restrictions
        DB::statement("ALTER TABLE bookings MODIFY COLUMN `status` VARCHAR(30) NOT NULL DEFAULT 'waiting_verification'");
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('message');
        });

        DB::statement("ALTER TABLE bookings MODIFY COLUMN `status` ENUM('pending_payment', 'waiting_verification', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'pending_payment'");
    }
};
