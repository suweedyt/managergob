<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try MySQL style first, fallback to Postgres if it fails
        try {
            DB::statement("ALTER TABLE `tramites` MODIFY `title_short` VARCHAR(100) NULL");
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE tramites ALTER COLUMN title_short DROP NOT NULL');
            } catch (\Throwable $e) {
                // last-resort: ignore, user can adjust manually
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL
        try {
            DB::statement("ALTER TABLE `tramites` MODIFY `title_short` VARCHAR(100) NOT NULL");
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE tramites ALTER COLUMN title_short SET NOT NULL');
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
