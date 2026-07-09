<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // কল্যাণ / ভাড়া collections carry no program, so program_id must allow NULL.
        // The live column was NOT NULL (the original migration's nullable() never
        // took effect here). Raw SQL avoids needing doctrine/dbal for ->change().
        DB::statement('ALTER TABLE `khedmots` MODIFY `program_id` BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Intentionally not reverting to NOT NULL: rows may legitimately hold NULL.
    }
};
