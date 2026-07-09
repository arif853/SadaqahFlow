<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khedmots', function (Blueprint $table) {
            // Distinguishes plain খেদমত records from কল্যাণ / ভাড়া collections,
            // which now live in the same table but on their own screens.
            $table->string('type')->default('khedmot')->index()->after('member_id');
            // Which month a কল্যাণ / ভাড়া collection is for (HTML month input: "YYYY-MM").
            $table->string('month', 7)->nullable()->after('type');
        });

        // Every existing record predates this feature, so it is a plain খেদমত.
        DB::table('khedmots')->whereNull('type')->update(['type' => 'khedmot']);
    }

    public function down(): void
    {
        Schema::table('khedmots', function (Blueprint $table) {
            $table->dropColumn(['type', 'month']);
        });
    }
};
